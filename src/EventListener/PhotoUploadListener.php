<?php
/**
 * Photo upload listener.
 */

namespace App\EventListener;

use App\Entity\Photo;
use App\Service\FileUploader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class PhotoUploadListener.
 */
class PhotoUploadListener
{
    /**
     * Uploader service.
     *
     * @var \App\Service\FileUploader|null
     */
    protected $uploaderService = null;
    /**
     * @var \Symfony\Component\Filesystem\
     */
    protected $filesystem = null;

    /**
     * PhotoUploadListener constructor.
     * @param FileUploader $fileUploader
     * @param Filesystem $filesystem
     */
    public function __construct(FileUploader $fileUploader, Filesystem $filesystem)
    {
        $this->uploaderService = $fileUploader;
        $this->filesystem = $filesystem;
    }

    /**
     * Pre persist.
     *
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args Event args
     *
     * @throws \Exception
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    /**
     * Pre update.
     *
     * @param \Doctrine\ORM\Event\PreUpdateEventArgs $args Event args
     *
     * @throws \Exception
     */
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Photo) {
            return;
        }

        if ($fileName = $entity->getPhoto()) {
            $entity->setPhoto(
                new File(
                    $this->uploaderService->getTargetDir().'/'.$fileName
                )
            );
        }
    }


    /**
     * Upload file.
     *
     * @param \App\Entity\Photo $entity Photo entity
     *
     * @throws \Exception
     */
    private function uploadFile($entity): void
    {
        if (!$entity instanceof Photo) {
            return;
        }

        $file = $entity->getPhoto();
        if ($file instanceof UploadedFile) {
            $filename = $this->uploaderService->upload($file);
            $entity->setPhoto($filename);
        } else if ($file instanceof File) {
            $entity->setPhoto($file->getFilename());
        }
    }

    /**
     * Pre remove.
     *
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->removePhoto($entity);
    }
// ...
    /**
     * Remove file from disk.
     *
     * @param \App\Entity\Photo $entity Photo entity
     */
    private function removePhoto($entity): void
    {
        if (!$entity instanceof Photo) {
            return;
        }

        $file = $entity->getPhoto();
        if ($file instanceof File) {
            $this->filesystem->remove($file->getPathname());
        }
    }
}
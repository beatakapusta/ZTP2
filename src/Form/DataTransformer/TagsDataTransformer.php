<?php
/**
 * Tags data transformer.
 */

namespace App\Form\DataTransformer;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class TagsDataTransformer.
 */
class TagsDataTransformer implements DataTransformerInterface
{
    /**
     * Tag repository.
     *
     * @var \App\Repository\TagRepository|null
     */
    private $repository = null;

    /**
     * TagsDataTransformer constructor.
     *
     * @param \App\Repository\TagRepository $repository Tag repository
     */
    public function __construct(TagRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Transform array of tags to string of names.
     *
     * @param \Doctrine\Common\Collections\Collection $tags Tags entity collection
     *
     * @return string Result
     */
    public function transform($tags): string
    {
        if (null == $tags) {
            return '';
        }

        $tagNames = [];

        foreach ($tags as $tag) {
            $tagNames[] = $tag->getTag();
        }

        return implode(',', $tagNames);
    }

    /**
     * Transform string of tag names into array of Tag entities.
     *
     * @param string $value String of tag names
     *
     * @return array Result
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function reverseTransform($value): array
    {
        $tagNames = explode(',', $value);

        $tags = [];

        foreach ($tagNames as $tagName) {
            if ('' !== trim($tagName)) {
                $tag = $this->repository->findOneByTag(strtolower($tagName));
                if (null == $tag) {
                    $tag = new Tag();
                    $tag->setTag($tagName);
                    $this->repository->save($tag);
                }
                $tags[] = $tag;
            }
        }

        return $tags;
    }
}
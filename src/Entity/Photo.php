<?php
/**
 * Photo entity.
 */
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Photo.
 *
 * @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
 * @ORM\Table(
 *     name="photos",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(
 *              name="UQ_photos_1",
 *              columns={"photo"},
 *          ),
 *     },
 * )
 *
 * @UniqueEntity(
 *     fields={"photo"}
 * )
 */
class Photo
{
    /**
     * Primary key.
     *
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Photo
     * @ORM\Column(
     *     type="string",
     *     length=191,
     *     nullable=false,
     *     unique=true,
     * )
     *
     * @Assert\NotBlank
     * @Assert\Image(
     *     maxSize = "1024k",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg", "image/jpeg", "image/pjpeg"},
     * )
     */
    private $photo;

    /**
     * @ORM\OneToOne(
     *     targetEntity="App\Entity\Recipe", inversedBy="photo", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="recipe_id", referencedColumnName="id")
     */
    private $recipe;

    /**
     * Getter for Id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param string $photo
     * @return Photo
     */
    public function setPhoto($photo): void
    {
        $this->photo = $photo;

    }

    /**
     * @return Recipe|null
     */
    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    /**
     * @param Recipe $recipe
     * @return Photo
     */
    public function setRecipe(Recipe $recipe): self
    {
        $this->recipe = $recipe;

        // set the owning side of the relation if necessary
        if ($this !== $recipe->getPhoto()) {
            $recipe->setPhoto($this);
        }

        return $this;
    }

    /**
     * @see \Serializable::serialize()
     *
     * @return string Serialized object
     */
    public function serialize(): string
    {
        $file = $this->getPhoto();

        return serialize(
            [
                $this->id,
                ($file instanceof Photo ) ? $file->getPhoto() : $file,
            ]
        );
    }

    /**
     * @see \Serializable::unserialize()
     *
     * @param string $serialized Serialized object
     */
    public function unserialize($serialized): void
    {
        list($this->id) = unserialize($serialized);
    }
}

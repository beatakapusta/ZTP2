<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecipeRepository")
 * @ORM\Table(name="recipes")
 */
class Recipe
{
    /**
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See http://symfony.com/doc/current/best_practices/configuration.html#constants-vs-configuration-options
     *
     * @constant int NUMBER_OF_ITEMS
     */
    const NUMBER_OF_ITEMS = 10;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(
     *     type="string",
     *     length=45
     *     )
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="45",
     * )
     */
    private $name;

    /**
     * @ORM\Column(
     *     type="string",
     *     length=255
     *     )
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="255",
     * )
     */
    private $text;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag",
     *     inversedBy="recipes",
     *     orphanRemoval=true
     * )
     * @ORM\JoinTable(name="recipes_tags")
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity="RecipeIngredient", mappedBy="recipe", fetch="EXTRA_LAZY")
     */
    private $recipe_ingredient;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Photo", mappedBy="recipe", cascade={"persist", "remove"})
     */
    private $photo;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Recipe
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return Recipe
     */
    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getRecipe_ingredient(): Collection
    {
        return $this->recipe_ingredient;
    }

    /**
     * @param RecipeIngredient $recipe_ingredient
     * @return Recipe
     */
    public function addRecipe_ingredient(RecipeIngredient $recipe_ingredient): self
    {
        if (!$this->recipe_ingredient->contains($recipe_ingredient)) {
            $this->recipe_ingredient[] = $recipe_ingredient;
        }

        return $this;
    }

    /**
     * @param RecipeIngredient $recipe_ingredient
     * @return Recipe
     */
    public function removeRecipe_ingredient(RecipeIngredient $recipe_ingredient): self
    {
        if ($this->recipe_ingredient->contains($recipe_ingredient)) {
            $this->recipe_ingredient->removeElement($recipe_ingredient);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param Photo|null $photo
     * @return $this
     */
    public function setPhoto(Photo $photo)
    {
        $this->photo = $photo;

        return $this;
    }
}

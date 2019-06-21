<?php

namespace App\Entity;

use App\Form\RecipeIngredientType;
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
     * @ORM\OneToMany(targetEntity="App\Entity\RecipeIngredient", mappedBy="recipe", cascade={"persist"})
     */
    private $recipeIngredients;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Photo", mappedBy="recipe", cascade={"persist", "remove"})
     */
    private $photo;

    /**
     * Recipe constructor.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->recipeIngredients = new ArrayCollection();
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
    public function getRecipeIngredients(): ?Collection
    {
        return $this->recipeIngredients;
    }

    /**
     * @param RecipeIngredient $recipe_ingredient
     * @return Recipe
     */
    public function addRecipeIngredient(RecipeIngredient $recipeIngredient): self
    {
        if (!$this->recipeIngredients->contains($recipeIngredient)) {
            $this->recipeIngredients[] = $recipeIngredient;
        }
        return $this;
    }

    /**
     * @param RecipeIngredient $recipe_ingredient
     * @return Recipe
     */
    public function removeRecipeIngredient(RecipeIngredient $recipeIngredient): self
    {
        if ($this->recipeIngredients->contains($recipeIngredient)) {
            $this->recipeIngredients->removeElement($recipeIngredient);
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

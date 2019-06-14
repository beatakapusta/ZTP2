<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity(repositoryClass="App\Repository\IngredientRepository")
 * @ORM\Entity
 * @ORM\Table(name="ingredient")
 */
class Ingredient
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $name;

    /**
     * Code.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=45
     * )
     *
     * @Gedmo\Slug(fields={"name"})
     *
     *
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity="RecipeIngredient", mappedBy="ingredient")
     */
    private $ingredient_recipe;

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
     * @return Ingredient
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Ingredient
     */
    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getIngredientRecipe(): Collection
    {
        return $this->ingredient_recipe;
    }

    /**
     * @param RecipeIngredient $ingredient_recipe
     * @return Ingredient
     */
    public function addIngredientRecipe(RecipeIngredient $ingredient_recipe): self
    {
        if (!$this->ingredient_recipe->contains($ingredient_recipe)) {
            $this->ingredient_recipe[] = $ingredient_recipe;
        }

        return $this;
    }

    /**
     * @param RecipeIngredient $ingredient_recipe
     * @return Ingredient
     */
    public function removeIngredientRecipe(RecipeIngredient $ingredient_recipe): self
    {
        if ($this->ingredient_recipe->contains($ingredient_recipe)) {
            $this->ingredient_recipe->removeElement($ingredient_recipe);
        }

        return $this;
    }
}

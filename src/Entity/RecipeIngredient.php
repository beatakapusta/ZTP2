<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecipeIngredientRepository")
 * @ORM\Entity
 * @ORM\Table(name="recipe_ingredient")
 */
class RecipeIngredient
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
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity="Recipe", inversedBy="recipeIngredients")
     * @ORM\JoinColumn(nullable=false)q1
     */
    private $recipe;

    /**
     * @ORM\ManyToOne(targetEntity="Ingredient", inversedBy="ingredientRecipe", cascade={"persist"}))
     * @ORM\JoinColumn(nullable=false)
     */
    private $ingredient;

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
    public function getAmount(): ?string
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     * @return RecipeIngredient
     */
    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRecipe()
    {
        return $this->recipe;
    }

    /**
     * @param $recipe
     * @return $this
     */
    public function setRecipe($recipe)
    {
        $this->recipe = $recipe;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIngredient()
    {
        return $this->ingredient;
    }

    /**
     * @param $ingredient
     * @return $this
     */
    public function setIngredient($ingredient)
    {
        $this->ingredient = $ingredient;

        return $this;
    }
}

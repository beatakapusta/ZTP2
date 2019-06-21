<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;



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
     *
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="45",
     * )
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
     * @Assert\Length(
     *     min="3",
     *     max="45",
     * )
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecipeIngredient", mappedBy="ingredient", cascade={"persist"})
     */
    private $ingredientRecipe;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Unit", inversedBy="ingredients")
     * @ORM\JoinColumn(nullable=false)
     */
    private $unit;

    /**
     * Ingredient constructor.
     */
    public function __construct()
    {
        $this->ingredientRecipe = new ArrayCollection();
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
        return $this->ingredientRecipe;
    }

    /**
     * @param RecipeIngredient $ingredient_recipe
     * @return Ingredient
     */
    public function addIngredientRecipe(RecipeIngredient $ingredientRecipe): self
    {
        if (!$this->ingredientRecipe->contains($ingredientRecipe)) {
            $this->ingredientRecipe[] = $ingredientRecipe;
        }

        return $this;
    }

    /**
     * @param RecipeIngredient $ingredient_recipe
     * @return Ingredient
     */
    public function removeIngredientRecipe(RecipeIngredient $ingredientRecipe): self
    {
        if ($this->ingredientRecipe->contains($ingredientRecipe)) {
            $this->ingredientRecipe->removeElement($ingredientRecipe);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->getName();
    }

    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    public function setUnit(?Unit $unit): self
    {
        $this->unit = $unit;

        return $this;
    }
}

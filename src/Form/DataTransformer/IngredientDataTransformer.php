<?php
/**
 * Ingredient data transformer.
 */

namespace App\Form\DataTransformer;

use App\Entity\RecipeIngredient;
use App\Entity\Recipe;
use App\Form\RecipeIngredientType;
use App\Repository\IngredientRepository;
use App\Repository\RecipeIngredientRepository;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class IngredientsDataTransformer.
 */
class IngredientDataTransformer implements DataTransformerInterface
{
    /**
     * Ingredient repository.
     *
     * @var \App\Repository\IngredientRepository|null
     */
    private $repository = null;

    /**
     * IngredientsDataTransformer constructor.
     *
     * @param \App\Repository\IngredientRepository $repository Ingredient repository
     */
    public function __construct(RecipeIngredientRepository $repository, IngredientRepository $ingredientRepository)
    {
        $this->repository = $repository;
        $this->repository = $ingredientRepository;
    }

    /**
     * Transform array of ingredients to string of names.
     *
     * @param \Doctrine\Common\Collections\Collection $ingredients Ingredients entity collection
     *
     * @return string Result
     */
    public function transform($ingredients)
    {
//        dump($ingredients);
//        if (null == $ingredients) {
//            return '';
//        }
//
        $ingredientNames = [];
        foreach ($ingredients as $ingredient) {
            $ingredientNames[] = $ingredient->getName();
        }

        return $ingredients;
    }

    /**
     * Transform string of ingredient names into array of ingredient entities.
     *
     * @param string $value String of ingredient names
     *
     * @return array Result
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function reverseTransform($value): array
    {
 //       dump($value);
//        die();
//        $ingredientNames = explode(',', $value);
//        dump($ingredientNames);
//
//        $ingredients = [];
//
        foreach ($value as $ingredientName) {
            dump($ingredientName);
                if ('' !== $ingredientName) {
                    $ingredient = $this->repository->findOneByName($ingredientName);
                    if (null == $ingredient) {
                        $ingredient = new RecipeIngredient(1);
                        $ingredient->setAmount($ingredientName);
                        $this->repository->save($ingredient);
                    }
                    $ingredients[] = $ingredient;
                }
            }

        return $ingredients;
    }
}
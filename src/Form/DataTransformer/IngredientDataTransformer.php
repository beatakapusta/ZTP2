<?php
/**
 * Ingredient data transformer.
 */

namespace App\Form\DataTransformer;

use App\Entity\Ingredient;
use App\Entity\RecipeIngredient;
use App\Entity\Recipe;
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
        $this->ingredientRepository = $ingredientRepository;
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
        //dump($ingredients);
        if (null == $ingredients) {
            return "";
      }

        $ingredientNames = [];
        foreach ($ingredients as $ingredient) {
            $ingredientNames[] = $ingredient->getIngredient();
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

        $ingredientsData = [];
        foreach ($value as $collection) {
            if ('' !== $collection) {

                $ingredients = $collection->getIngredient($collection);
                $ingredientName = $ingredients->getName($ingredients);
                $ingredient = $this->ingredientRepository->findOneByName(strtolower($ingredientName));

                $amount = $collection->getAmount($collection);
                $recipeIngredient = $this->repository->findOneByAmount(strtolower($amount));
                if (null == $ingredient) {
                    $ingredientNew = new Ingredient(1);
                    $ingredientNew->setName($ingredientName);
                    $this->ingredientRepository->save($ingredientNew);
                   // $ingredientId = $ingredientNew->getId();
                    $recipeIngredientNew = new RecipeIngredient(1);
                    $recipeIngredientNew->setAmount($amount);
                    $recipeIngredientNew->setIngredient($ingredientNew);
                   // $this->repository->save($recipeIngredientNew);
                    $ingredientsData[] = $recipeIngredientNew;
                } else {
                    $recipeIngredientNew = new RecipeIngredient(1);
                    $recipeIngredientNew->setAmount($amount);
                    $recipeIngredientNew->setIngredient($ingredient);
                    // $this->repository->save($recipeIngredientNew);
                    $ingredientsData[] = $recipeIngredientNew;
                }
            }
        }
        return $ingredientsData;
    }
}
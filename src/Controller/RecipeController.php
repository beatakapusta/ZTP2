<?php
/**
 * Recipe controller.
 */
namespace App\Controller;
use App\Entity\Recipe;
use App\Entity\Photo;
use App\Entity\RecipeIngredient;
use App\Form\RecipeType;
use App\Repository\PhotoRepository;
use App\Repository\RecipeIngredientRepository;
use App\Repository\RecipeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * Class RecipeController.
 *
 * @Route("/recipes")
 */
class RecipeController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\RecipeRepository        $repository Recipe repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator  Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route(
     *     "/",
     *     name="recipe_index",
     * )
     */
    public function index(Request $request, RecipeRepository $repository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            Recipe::NUMBER_OF_ITEMS
        );
        return $this->render(
            'recipe/index.html.twig',
            ['pagination' => $pagination]
        );
    }
    /**
     * View action.
     *
     * @param \App\Entity\Recipe $recipe Recipe entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     name="recipe_view",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function view(Recipe $recipe): Response
    {
        return $this->render(
            'recipe/view.html.twig',
            ['recipe' => $recipe]
        );
    }
    /**
     * New action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\RecipeRepository        $repository Recipe repository
     *
     * @param \App\Repository\RecipeIngredientRepository        $recipeIngredientRepository repository RecipeIngredient repository

     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/new",
     *     methods={"GET", "POST"},
     *     name="recipe_new",
     * )
     */
    public function new(Request $request, RecipeRepository $repository, RecipeIngredientRepository $recipeIngredientRepository): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $photo = $recipe->getPhoto();
          //  $photo->setRecipe($recipe);

            $recipeIngredients = $recipe->getRecipeIngredients();
            $recipeNew = new Recipe();
            $recipeName = $recipe->getName();
            $recipeText = $recipe->getText();
            $recipeTags = $recipe->getTags();
            $recipeNew->setName($recipeName);
            $recipeNew->setText($recipeText);
            $photo->setRecipe($recipeNew);
            foreach($recipeTags as $recipeTag){
                $recipeNew->addTag($recipeTag);
            }

            $repository->save($recipeNew);

            $recipeNew->getId();

            foreach ($recipeIngredients as $recipeIngredient){
                $recipeIngredient->setRecipe($recipeNew);
                $recipeIngredientRepository->save($recipeIngredient);
            }

            return $this->redirectToRoute('recipe_index');
        }
        return $this->render(
            'recipe/new.html.twig',
            ['form' => $form->createView()]
        );
    }
    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Recipe                          $recipe      Recipe entity
     * @param \App\Repository\RecipeRepository            $repository Recipe repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="recipe_edit",
     * )
     */
    public function edit(Request $request, Recipe $recipe, RecipeRepository $repository, Filesystem $filesystem, RecipeIngredientRepository $recipeIngredientRepository): Response
    {

        $photo = $recipe->getPhoto();

        $recipeIngredientsOld = $recipe->getRecipeIngredients();

        $originalRecipeIngredients = clone $recipeIngredientsOld;

        $originalPhoto = clone $photo;

        $form = $this->createForm(RecipeType::class, $recipe, ['method' => 'PUT']);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $formData = $form->getData();
            if ($formData->getPhoto() instanceof UploadedFile) {
                $file = $originalPhoto->getPhoto();
                $filesystem->remove($file->getPathname());
            }
            $recipeId =$recipe->getId();


            $recipeIngredientNew = new RecipeIngredient();
            $recipeIngredientNew = $formData->getRecipeIngredients();

            foreach ($originalRecipeIngredients as $recipeIngredient){
                $recipeIngredientRepository->delete($recipeIngredient);
            }

            foreach ($recipeIngredientNew as $recipeIngredient){
                $recipeIngredient->setRecipe($recipe);
                $recipeIngredientRepository->save($recipeIngredient);
            }
            // $this->repository->save($recipeIngredientNew);
            $repository->save($recipe);

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render(
            'recipe/edit.html.twig',
            [
                'form' => $form->createView(),
                'recipe' => $recipe,
            ]
        );
    }
    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Recipe                       $recipe       Recipe entity
     * @param \App\Repository\RecipeRepository            $repository  repository
     * @param \Symfony\Component\Filesystem\Filesystem  $filesystem Filesystem component
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="recipe_delete",
     * )
     */
    public function delete(Request $request, Recipe $recipe, RecipeRepository $repository): Response
    {
        $form = $this->createForm(FormType::class, $recipe, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($recipe->getTags() as $tag) {
                $recipe->removeTag($tag);
            }
            $repository->delete($recipe);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render(
            'recipe/delete.html.twig',
            [
                'form' => $form->createView(),
                'recipe' => $recipe,
            ]
        );
    }
}
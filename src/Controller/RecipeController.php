<?php
/**
 * Recipe controller.
 */
namespace App\Controller;
use App\Entity\Recipe;
use App\Entity\Photo;
use App\Form\RecipeType;
use App\Repository\PhotoRepository;
use App\Repository\RecipeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
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
    public function new(Request $request, RecipeRepository $repository, PhotoRepository $photoRepository): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           // dump($recipe);
//            die();
            //$photoData = $form -> get('photo') -> getData();
//            $photo = new Photo();
//            $photo->setPhoto($photoData);
//            $photo->setRecipe($repository->findOneBy(['id' => 4]));
//
//            $recipe->setPhoto($photo);
              $repository->save($recipe);
//
//            $photoRepository->save($photo);
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
    public function edit(Request $request, Recipe $recipe, RecipeRepository $repository): Response
    {
        //dump($recipe->getPhoto());

        $form = $this->createForm(RecipeType::class, $recipe, ['method' => 'PUT']);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {


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
     *
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
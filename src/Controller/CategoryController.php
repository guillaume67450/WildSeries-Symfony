<?php
namespace App\Controller;

use App\Entity\Season;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\ProgramRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * @Route("/cat", name="cat_index")
     * @return Response A response instance
     */
    public function indexFormCategory(CategoryRepository $categoryRepository): Response
    {


        return $this->render(
            'wild/categories.html.twig',
            [
                'categories' => $categoryRepository->findAll(),
            ]
        );
    }

    /**
     * @Route("category/{id}", name="category_show", methods={"GET"})
     */
    public function show(Category $category): Response
    {
        $programs = $this->getDoctrine()
        ->getRepository(Program::class)
        ->findBy(['Category' => $category->getId()],
            ['title' => 'ASC']
        );

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'programs' => $programs
        ]);
    }

    /**
     * @Route("/category/add", name="category_add")
     */
    public function insert(Request $request) : Response
    {
        // creates a task object and initializes some data for this example
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('cat_index');
        }

        return $this->render('wild/category-add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

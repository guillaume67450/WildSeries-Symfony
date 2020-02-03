<?php
// src/Controller/DefaultController.php
namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

Class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository, ProgramRepository $programRepository): Response
    {
        return $this->render('home.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'programs' => $programRepository->findAll(),
        ]);
    }
}
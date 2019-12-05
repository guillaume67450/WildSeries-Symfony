<?php
// src/Controller/WildController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Program;
use App\Entity\Category;

/** @Route("/wild", name="wild_") */
Class WildController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @return Response A response instance
    */
    public function index() :Response
    {
        $programs = $this->getDoctrine()->getRepository(Program::class)->findAll();
        //var_dump($programs);
        if (!$programs) {
            throw $this->createNotFoundException('No program found in Program\'s table.');
        }

        return $this->render('wild/index.html.twig',
                ['programs' => $programs]
            );
    }

    /**
    * Getting a program with a formatted slug for title
    *
    * @param string $slug The slugger
    * @Route("/show/{slug<^[a-z0-9-]+$>}", defaults={"slug" = null}, name="show")
    * @return Response
    */
    public function show(?string $slug):Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with '.$slug.' title, found in program\'s table.'
            );
        }

        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug'  => $slug,
        ]);
    }


     /**
     * Getting all programs from a category
     *
     * @param string $categoryName The Category
     * @Route("/category/{categoryName}", name="show_category")
     * @return Response
     */
    public function showByCategory(string $categoryName):Response
    {
        if (!$categoryName) {
            throw $this
                ->createNotFoundException('No category has been sent to find the programs.');
        }

        $category = $this->getDoctrine()->getRepository(Category::class)->findOneBy(['name' => strtolower($categoryName)]);

        $programs = $this->getDoctrine()->getRepository(Program::class)->findBy(
            ['Category' => $category->getId()],
            ['id' => 'desc'],
            3,
            0
        );

        return $this->render(
            'wild/category.html.twig', [
            'category' => $category->getName(),
            'programs' => $programs,
        ]);

    }
/*
    /**
     * @Route("/show/{slug}",
     *   requirements={"slug"="[a-z0-9-]*"})
     *   name="show"
     * )
     */
    /*
    public function show($slug) :Response
    {   
        $nothing = "Aucune série sélectionnée, veuillez choisir une série";
        $title = $nothing;

        if ($slug) {
            $title = ucwords(str_replace('-',' ',$slug));
        }
        
        return $this->render('wild/show.html.twig',
        [ 
            'titre' => $title
        ]
        );
    }
    */

    
}
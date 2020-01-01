<?php
// src/Controller/WildController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Program;
use App\Entity\Category;
use App\Entity\Season;
use App\Entity\Episode;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ProgramSearchType;





/** @Route("/wild", name="wild_") */
class WildController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index(Request $request): Response
    {
        

        $form = $this->createForm(ProgramSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data = $form->getData();
            // $data contains $_POST data
            // TODO : Faire une recherche dans la BDD avec les infos de $data…
            $repo = $this->getDoctrine()
                ->getRepository(Program::class);
            $programs = $repo->searchByTitle($data['searchField']);

            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($task);
            // $entityManager->flush();
        
            return $this->render(
                'wild/index.html.twig',
                [
                    'programs' => $programs,
                    'form' => $form->createView(),
                ]
            );
 
        } else {
            $programs = $this->getDoctrine()->getRepository(Program::class)->findAll();

            if (!$programs) {
                throw $this->createNotFoundException('No program found in Program\'s table.');
            }
        }

        return $this->render(
            'wild/index.html.twig',
            [
                'programs' => $programs,
                'form' => $form->createView()
            ]
        );
    }



    /**
     * Getting a program with a formatted slug for title
     *
     * @param string $slug The slugger
     * @Route("/show/{slug<^[a-z0-9-]+$>}", defaults={"slug" = null}, name="show")
     * @return Response
     */
    public function show(?string $slug): Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $slug = preg_replace(
            '/-/',
            ' ',
            ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with ' . $slug . ' title, found in program\'s table.'
            );
        }

        var_dump($program);

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
    public function showByCategory(string $categoryName): Response
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
            'wild/category.html.twig',
            [
                'category' => $category->getName(),
                'programs' => $programs,
            ]
        );
    }

    /**
     * Getting a season from a program
     *
     * @Route("/season/{id}", name="show_season")
     */
    public function showBySeason(int $id): Response
    {
        if (!$id) {
            throw $this
                ->createNotFoundException('No season has been sent to find this season.');
        }


        $season = $this->getDoctrine()->getRepository(Season::class)->find($id);

        return $this->render(
            'wild/season.html.twig',
            [
                'program' => $season->getProgram(),
                'episodes' => $season->getEpisodes(),
                'season' => $season,
            ]
        );
    }

    /**
     * Getting all seasons from a program
     *
     * @Route("/programSeasons/{id}", name="show_seasons")
     */
    public function showProgramSeasons(Program $program): Response
    {
        if (!$program) {
            throw $this
                ->createNotFoundException('No season has been sent to find this season.');
        }


        $seasons = $program->getSeasons();
        dump($seasons);
        return $this->render(
            'wild/seasons.html.twig',
            [
                'program' => $program,
                'seasons' => $seasons,
            ]
        );
    }

    /**
     * Getting an episode from a season
     *
     * @Route("/episode/{id}", name="show_episode")
     */
    public function showEpisode(Episode $episode): Response
    {
        if (!$episode) {
            throw $this
                ->createNotFoundException('No episode has been sent to find the episod.');
        }

        $season = $episode->getSeasonId();

        return $this->render(
            'wild/episode.html.twig',
            [
                'program' => $season->getProgram(),
                'episode' => $episode,
                'season' => $episode->getSeasonId(), // note : c'est mon nom d'objet où il y a un ID en trop, mais l'objet fait bien référence à Season
            ]
        );
    }



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

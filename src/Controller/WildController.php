<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\CategoryType;
use App\Services\SlugifyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ProgramRepository;



/**
 * @Route("/wild", name="wild_")
 */
class WildController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @return Response
     */
     public function index(Request $request, ProgramRepository $programRepository) :Response
     {
         $programs = $this->getDoctrine()->getRepository(Program::class)->findAll();
 
         if(!$programs) {
             throw $this->createNotFoundException('No program found in program\'s table.');
         }
 
         $category = new Category();
         $form = $this->createForm(CategoryType::class, $category);
         $form->handleRequest($request);
 
         if ($form->isSubmitted()) {
              $categoryManager = $this->getDoctrine()->getManager();
             $categoryManager->persist($category);
             $categoryManager->flush();
 
             return $this->redirectToRoute('wild_index');
         }
 
         return $this->render('wild/index.html.twig', [
             'programs' => $programRepository->findAllWithCategoriesAndActor(),
             'form' => $form->createView(),
         ]);
     }

    /**
     * @param string $slug The slugger
     * @Route("/show/{slug<^[a-z0-9-]+$>}",
     *     name="show",
     *     defaults={"slug"=null},
     *  )
     * @return Response
     */
    public function show(?string $slug): Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        if ($slug == null) {
            $slug = "Aucune série sélectionnée, veuillez choisir une série";
        } else {
            $slug = SlugifyService::unslugify($slug);
        }
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with ' . $slug . ' title, found in program\'s table.'
            );
        }

        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug' => $slug,
        ]);
    }

    /**
     * @param string $categoryName
     * @Route("/showByCategory/{categoryName<^[A-Za-z0-9-]+$>}",
     *     name="showByCategory",
     *     defaults={"categoryName"=null},
     *  )
     * @return Response
     */
    public function showByCategory(?string $categoryName): Response
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => strtolower($categoryName)]);
        if (!$category) {
            throw $this->createNotFoundException(
                'No category with ' . $categoryName . ' name, found in category\'s table.'
            );
        }
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category' => $category->getId()],
                ['id' => 'DESC'],
                3
            );

        $slugs = SlugifyService::multiSlugify($programs);

        return $this->render('wild/showByCategory.html.twig', [
            'category' => $categoryName,
            'programs' => $programs,
            'slugs' => $slugs,
        ]);
    }

    /**
     * @param string $slug The slugger
     * @Route("/showByProgram/{slug<^[a-z0-9-]+$>}",
     *     name="showByProgram",
     *     defaults={"slug"=null},
     *  )
     * @return Response
     */
    public function showByProgram(?string $slug): Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        if ($slug == null) {
            $slug = "Aucune série sélectionnée, veuillez choisir une série";
        } else {
            $slug = SlugifyService::unslugify($slug);
        }
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with ' . $slug . ' title, found in program\'s table.'
            );
        }
        return $this->render('wild/showByProgram.html.twig',
            [
                'program' => $program,
                'slug' => $slug,
            ]
        );
    }

    /**
     * @param integer $id
     * @Route("/showBySeason/{id<\d+>}",
     *     name="showBySeason",
     *  )
     * @return Response
     */
    public function showBySeason(int $id): Response
    {
        if (!$id) {
            throw $this
                ->createNotFoundException('No id has been sent to find a season in season\'s table.');
        }

        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->find($id);
        if (!$season) {
            throw $this->createNotFoundException(
                'No season with ' . $id . ' number, found in season\'s table.'
            );
        }

        $slug = SlugifyService::slugify($season->getProgram()->getTitle());

        return $this->render('wild/showBySeason.html.twig',
            [
                'season' => $season,
                'slug' => $slug,
            ]
        );
    }

    /**
     * @param integer $id
     * @Route("/showEpisode/{slug}", name="showEpisode")
     */
    public function showEpisode(Episode $episode): Response
    {
        $season = $episode->getSeasonId();
        $program = $season->getProgram();

        $slug = SlugifyService::slugify($program->getTitle());

        return $this->render('wild/showEpisode.html.twig',
            [
                'episode' => $episode,
                'program' => $program,
                'season' => $season,
                'slug' => $slug,
            ]);
    }
}

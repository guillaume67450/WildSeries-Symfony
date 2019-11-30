<?php
// src/Controller/WildController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

Class WildController extends AbstractController
{
    /**
     * @Route("/wild", name="wild_index")
    */
    public function index() :Response
    {
        return $this->render('wild/index.html.twig', [
                'website' => 'Wild Séries',
        ]);
    }

    /**
    * @Route("/wild/show/{slug<^[a-z0-9-]+$>}",
    *     defaults={"slug"=null},
    *     name="wild_show"
    * )
     * 
     * slug wcs
     */
    public function show($slug) :Response
    {   
        return $this->render('wild/show.html.twig',
        [ 
        'titre' => $slug
        ]
        );
    }

/*
    static public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
    
        // trim
        $text = trim($text, '-');
    
        // transliterate
        if (function_exists('iconv'))
        {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }
    
        // lowercase
        $text = strtolower($text);
    
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
    
        if (empty($text))
        {
            return 'n-a';
        }
    
        return $text;
    }
*/

}
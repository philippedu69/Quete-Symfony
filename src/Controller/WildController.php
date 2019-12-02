<?php
// src/Controller/WildController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/wild", name="wild_")
 */

class WildController extends AbstractController
{
    /**
     * @Route("", name="index")
    */
    public function index() :Response
    {
        return $this->render('/index.html.twig', [
            'website' => 'Wild Séries',
        ]);
    }

    /**
     * @Route("/show/{slug}",
     * requirements={"slug"="[a-z0-9-]+$"},
     * defaults={"slug"="", "Aucune série sélectionnée, veuillez choisir une série"}, name="show")
     */
    public function show(string $slug) :Response
    {
        $slug = str_replace("-", " ", $slug);
        return $this->render('wild/show.html.twig', ['slug' => ucwords($slug)]);
    }
}
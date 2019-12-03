<?php
// src/Controller/WildController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Program;
use App\Entity\Category;

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
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();
        if(!$programs) {
            throw $this->createNotFoundException(
                'No program found in program\'s table'
            );
        }
        return $this->render('wild/index.html.twig', [
            'programs' => $programs]
        );
    }

    /**
     * @param string $slug The slugger
     * @Route("/show/{slug}",
     * requirements={"slug"="[a-z0-9-]+$"},
     * defaults={"slug"="Aucune série sélectionnée, veuillez choisir une série"}, name="show")
     * @return Response
     */
    public function show(string $slug) :Response
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
        return $this->render('wild/show.html.twig', ['slug' => $slug, 'program' => $program]);
    }

    /**
     * @Route("/category/{categoryName}",
     * requirements={"categoryName"="[a-z0-9-]+$"},
     * defaults={"categoryName"="Aucune catégorie sélectionnée, veuillez choisir une catégorie"}, name="show_category")
     * @return Response
     */
    public function showByCategory(string $categoryName) :Response
    {
        if (!$categoryName) {
            throw $this
                ->createNotFoundException('No category has been sent to find a program in category\'s table.');
        }
        $categoryName = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($categoryName)), "-")
        );
        $categoryName = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => mb_strtolower($categoryName)]);
        $categoryName = $categoryName->getId();

        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category'=>$categoryName],
                ['id' => 'desc'],
                3,
                0);

        if (!$programs) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }
        return $this->render('wild/category.html.twig', ['category' => $categoryName, 'programs' => $programs]);
    }
}
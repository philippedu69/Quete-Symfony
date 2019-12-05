<?php
// src/Controller/WildController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Program;
use App\Entity\Category;
use App\Entity\Season;

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
     * defaults={"slug"=""}, name="show")
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
     * defaults={"categoryName"=""}, name="show_category")
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

    /**
     * @Route("/program/{programName}",
     * requirements={"programName"="[a-z0-9-]+$"},
     * defaults={"programName"=""}, name="show_program")
     * @return Response
     */
     public function showByProgram(string $programName) :Response
     {
         $programName = preg_replace(
             '/-/',
             ' ', ucwords(trim(strip_tags($programName)), "-")
         );
         $programName = $this->getDoctrine()
             ->getRepository(Program::class)
             ->findOneBy(['title' => mb_strtolower($programName)]);


         if (!$programName) {
             throw $this
                 ->createNotFoundException('No program has been sent to find a program in program\'s table.');
         }

         $seasons = $this->getDoctrine()
             ->getRepository(Season::class)
             ->findBy(['program' => $programName]);

         if (!$seasons) {
             throw $this->createNotFoundException(
                 'No season found in season\'s table.'
             );
         }

         return $this->render('wild/program.html.twig', [
             'program' => $programName,
             'seasons' => $seasons
         ]);

     }

    /**
     * @Route("/season/{id}",
     * defaults={"id"=""}, name="showSeason")
     * @return Response
     */
    public function showBySeason(int $id) :Response
    {

        if (!$id) {
            throw $this->createNotFoundException(
                'No season found in season\'s table.'
            );
        }
        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->find(['id' => $id]);


        return $this->render('wild/season.html.twig', ['season' => $season]);
    }
}
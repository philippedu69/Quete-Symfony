<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Episode;
use App\Form\CommentType;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Program;
use App\Entity\Category;
use App\Entity\Season;
use App\Entity\Actor;
use Symfony\Component\Security\Core\User\UserInterface;


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
     * @param string $categoryName
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
            ->findBy(['category'=>$categoryName]);

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
     * @param string $programName
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
     * @param int $id
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

    /**
     * @Route("/episode/newcomm/{id}", name="episodeComments")
     * @param Request $request
     * @param Episode $episode
     * @param $user
     * @return Response
     * @throws Exception
     */
    public function newComment(Request $request, Episode $episode, ?UserInterface $user): Response
    {

        $comment = new Comment();
        $comment->setEpisode($episode);
        $comment->setAuthor($user);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        dump($comment);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new\DateTime('now'));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('wild_episodeComments', [
                'id' =>$episode->getId(),
                'email' => $user->getUsername()
                ]);
        }
        return $this->render('wild/episode.html.twig', [
            'comment' =>$comment,
            'episode'=>$episode,
            'formComment' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="commentDelete", methods={"DELETE"})
     * @param Request $request
     * @param Comment $comment
     * @param Episode $episode
     * @return Response
     */

    public function delete(Request $request, Comment $comment, Episode $episode): Response
    {


        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('wild_episodeComments');
    }

    /**
     * @Route("/episode/{id}", name="showEpisode")
     * @param Episode $episode
     * @return Response
     */

    public function showEpisode(Episode $episode) :Response
    {
        if (!$episode) {
            throw $this->createNotFoundException(
                'No episode found in episode\'s table.'
            );
        }
        return $this->render('wild/episode.html.twig', ['episode' => $episode]);
    }

    /**
     * @Route("/actor/{id}", name="showActor")
     * @param Actor $actor
     * @return Response
     */

    public function showActor(Actor $actor) :Response
    {
        if (!$actor){
            throw $this->createNotFoundException(
                'No actor found in actor\'s table.'
            );
        }
        return $this->render('wild/actor.html.twig', ['actor' => $actor]);
    }


}
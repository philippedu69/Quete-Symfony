<?php


namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/comment")
 */

class CommentController extends AbstractController
{
    /**
     * @Route("/", name="comment_index", methods={"GET"})
     * @param CommentRepository $commentRepository
     * @return Response
     */
    public function index(CommentRepository $commentRepository): Response
    {
        return $this->render('comment/index.html.twig', [
            'comments'=>$commentRepository->findAll(),
        ]);
    }


    /**
     * @Route("/{id}", name="comment_show")
     * @param int $id
     * @return Response
     */
    public function show(int $id): Response
    {

        $comment = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->find(['id' => $id]);


        return $this->render('comment/show.html.twig', [
            'comment' => $comment,

        ]);
    }
}
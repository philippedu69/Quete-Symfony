<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     * @return Response
     */
    public function index() :Response
    {

        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy([], ['id' => 'DESC'],
                3,
                0);

        return $this->render('/home.html.twig', [
            'hello' => 'Bienvenue',
            'programs' => $programs
        ]);
    }

    /**
     * @Route("/my_profile", name="app_profile")
     * @return Response
     */
    public function showProfile(): Response
    {

        $user = $this->getUser();

        return $this->render('/register.html.twig', [
            'user'=> $user
        ]);
        dump($user);
        die();
    }

}
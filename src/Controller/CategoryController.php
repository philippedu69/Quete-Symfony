<?php


namespace App\Controller;



use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Category;
use App\Form\CategoryType;


class CategoryController extends AbstractController
{


    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/wild/category", name="wild_form")
     * @param Request $request
     * @return Response
     */

    public function newCategory(Request $request):Response
    {


        $category = new Category();
        $categoryForm = $this->createForm(CategoryType::class, $category);

        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($category);
            $manager->flush();
        }
        return $this->render('wild/form.html.twig', [
            'categoryForm' => $categoryForm->createView(),
            'category'=> $category,
        ]);
    }

}
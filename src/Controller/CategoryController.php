<?php


namespace App\Controller;



use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Category;
use App\Form\CategoryType;


class CategoryController extends AbstractController
{
    /**
     * @Route("/wild/category", name="wild_form")
     */

    public function newCategory(Request $request):Response
    {
        $manager = $this->getDoctrine()->getManager();

        $category = new Category();
        $categoryForm = $this->createForm(CategoryType::class, $category);

        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            $manager->persist($category);
            $manager->flush();
        }
        return $this->render('wild/form.html.twig', [
            'categoryForm' => $categoryForm->createView(),
            'category'=> $category,
        ]);
    }

}
<?php


namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class CategoryFixtures extends Fixture
{
    const CATEGORIES = [
        'Comedie',
        'Horreur',
        'Drame',
        'Jeunesse',
    ];

    public function load(ObjectManager $manager)
    {
        foreach(self::CATEGORIES as $key => $categoryName){
            $category = new Category();
            $category->setName($categoryName);
            $this->addReference('categorie_' . $key, $category);
            $manager->persist($category);
        }

        $manager->flush();
    }

}
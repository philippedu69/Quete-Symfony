<?php


namespace App\DataFixtures;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Service\Slugify;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\ExpressionLanguage\Tests\Node\Obj;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;


class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for($i = 0; $i <= 150; $i++){
            $episode = new Episode();
            $episode->setNumber($faker->randomDigit);
            $episode->setSynopsis($faker->text($maxNbChars = 200));
            $episode->setTitle($faker->sentence);
            $episode->setSeason($this->getReference('season'. random_int(0,29)));
            $slug = new Slugify();
            $slug = $slug->generate($episode->getTitle());
            $episode->setSlug($slug);
            $manager->persist($episode);
            $this->addReference('episode' .$i, $episode);

        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [SeasonFixtures::class];
        // TODO: Implement getDependencies() method.
    }
}
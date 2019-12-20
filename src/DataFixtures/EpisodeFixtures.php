<?php


namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;


class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for($i = 0; $i <= 150; $i++){
            $episode = new Episode();
            $episode->setNumber($faker->randomDigitNotNull);
            $episode->setSynopsis($faker->text($maxNbChars = 200));
            $episode->setTitle($faker->text($maxNbChars = 30));
            $episode->setSeason($this->getReference('season'. random_int(0,29)));
            $this->addReference('episode' .$i, $episode);

            $manager->persist($episode);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [SeasonFixtures::class];
        // TODO: Implement getDependencies() method.
    }
}
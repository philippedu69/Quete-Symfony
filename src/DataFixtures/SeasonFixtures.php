<?php


namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;


class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for($i = 0; $i <= 30; $i++){
            $season = new Season();
            $season->setNumber($faker->randomDigitNotNull);
            $season->setDescription($faker->text($maxNbChars = 20));
            $season->setYear($faker->year($max = 'now'));
            $manager->persist($season);
            $this->addReference('season' .$i, $season);
            $season->setProgram($this->getReference('program'. random_int(0,5)));

        }
        $manager->flush();

    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
        // TODO: Implement getDependencies() method.
    }
}
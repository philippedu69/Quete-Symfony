<?php


namespace App\DataFixtures;

use App\Entity\Actor;
use App\Service\Slugify;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;


class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    protected $faker;

    const ACTORS = [
        'Andrew Lincoln',
        'Norman Reedus',
        'kim Dickens',
        'Angela Bassett',
        'Lauren Cohan',
        'Danai Gurira',
    ];

    public function load(ObjectManager $manager)
    {

        foreach(Self::ACTORS as $key => $actorName){
            $actor = new Actor();
            $actor->setName($actorName);
            $slug = new Slugify();
            $slug = $slug->generate($actor->getName());
            $actor->setSlug($slug);
            $this->addReference('actor' .$key, $actor);
            $actor->addProgram($this->getReference('program'. random_int(0,5)));
            $manager->persist($actor);
        }

        $faker = Faker\Factory::create('fr_FR');


        for($i=6; $i <= 50; $i++){
            $actor = new Actor();
            $actor->setName($faker->name);
            $slug = new Slugify();
            $slug = $slug->generate($actor->getName());
            $actor->setSlug($slug);
            $this->addReference('actor' .$i, $actor);
            $actor->addProgram($this->getReference('program'. random_int(0,5)));
            $manager->persist($actor);

        }
        $manager->flush();
        // TODO: Implement load() method.
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
        // TODO: Implement getDependencies() method.
    }
}
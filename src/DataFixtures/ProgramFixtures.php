<?php


namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Program;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;


class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    const PROGRAMS = [
        'Walking dead' => [
            'summary' => 'Le policier Rick Grimes se réveille après un long coma.',
            'category' => 'categorie_1',
        ],
        'The Haunting of Hill house' => [
            'summary' => 'Plusieurs frères et soeurs qui, enfants, ont grandit dans la demeure de la peur...',
            'category' => 'categorie_1',
        ],
        'Americain Horror Story' => [
            'summary' => 'A chaque saison, son histoire. Americain Horror story nous embarque dans des récits glaçants...',
            'category' => 'categorie_1'
        ],
        'Love Death And Robots' => [
            'summary' => 'Un yaourt susceptible, des soldats lycanthropes, des robots déchainés',
            'category' => 'categorie_1'
        ],
        'Penny Dreadful' => [
            'summary' => 'Dans le Londres ancien, Vanessa Ives, jeune fille aux puissants pouvoirs hypnotiques, tue les méchants',
            'category' => 'categorie_1'
        ],
        'Fear the Walking dead' => [
            'summary' => 'La série se déroule au tout début de l\'épidemie relatée dans la série mère The Walkind Dead',
            'category' => 'categorie_1'
        ],
    ];

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        $i=0;
        foreach(self::PROGRAMS as $title => $data){
            $program = new Program();
            $program -> setTitle($title);
            $program -> setSummary($data['summary']);
            $program -> setPoster($faker->imageUrl($width = 480, $height = 640));
            $program->setCategory($this->getReference($data['category']));
            $this-> addReference('program' .$i, $program);
            $manager->persist($program);
            $i++;
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [CategoryFixtures::class];
        // TODO: Implement getDependencies() method.
    }

}
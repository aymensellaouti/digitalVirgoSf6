<?php

namespace App\DataFixtures;

use App\Entity\Hobby;
use App\Entity\Job;
use App\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PersonFixture extends Fixture implements DependentFixtureInterface
{
    private $jobs;
    private $hobbies;

    public function __construct()
    {}

    public function load(ObjectManager $manager): void
    {
        $this->jobs = $manager->getRepository(Job::class)->findAll();

        $this->hobbies = $manager->getRepository(Hobby::class)->findAll();

        $faker = Factory::create();
        for($i = 0; $i < 50 ; $i++) {
            $person1 = new Person();
            $person1->setJob($this->jobs[$i % count($this->jobs)] );
            $person1->setName($faker->name());
            $person1->setAge($faker->numberBetween(18, 65));
            $person1->setCin($faker->randomNumber(8));
            for ($j = $i; $j < $i + 3; $j++ ) {
                $person1->addHobby($this->hobbies[$j % count($this->hobbies)]);
            }
            $manager->persist($person1);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            JobFixture::class,
            HobbyFixture::class
        ];
    }
}

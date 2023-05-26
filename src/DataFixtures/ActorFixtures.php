<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

                for ($i = 0; $i < 10; $i++) {
                    $actor = new Actor();
                    $actor->setName($faker->name());
                    for ($j = 0; $j < 3; $j++) {
                        $uniqueNumber = $faker->numberBetween(1,5);
                        $actor->addProgram($this->getReference('programID_' . $uniqueNumber));
                    }
                    $manager->persist($actor);
                    $this->addReference('actor_' . $i, $actor);
                }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            ProgramFixtures::class,
        ];
    }
}
<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Season;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    /*const SEASONS = [
        ['1','2023','Durant cette saison on découvre l\'intrigue','La Reine Charlotte'],
        ['1','2010',"Dans le Kentucky, Rick Grimes, un policier, se réveille à l'hôpital après plusieurs semaines de coma provoqué par une fusillade qui a mal tourné.",'Walking Dead'],
        ['2','2011','Tous les survivants se retrouvent à la ferme des Greene.','Walking Dead']
    ];*/

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        /*foreach (self::SEASONS as $seasonUnique) {
            $season = new Season();
            $season->setNumber($seasonUnique[0]);
            $season->setYear($seasonUnique[1]);
            $season->setDescription($seasonUnique[2]);
            $season->setProgram($this->getReference('program_' . $seasonUnique[3]));
            $manager->persist($season);
        }*/

        $faker = Factory::create();

        for ($serie = 1; $serie < 6; $serie++) {
            for ($saison = 1; $saison < 6; $saison++) {
                $season = new Season();
                $season->setNumber($saison);
                $season->setYear($faker->year());
                $season->setDescription($faker->paragraphs(3, true));
                $season->setProgram($this->getReference('programID_' . $serie));
                $manager->persist($season);
                $manager->flush();
                $this->addReference('saison' . $saison . '_program_' . $serie, $season);
                /*$seasonNumber = $season->getNumber();
                $programName = $season->getProgram()->getTitle();
                $this->addReference('saison' . $seasonNumber . '_program_' . $programName, $season);*/

            }

        }
    }

    public function getDependencies()
    {
        return [
            ProgramFixtures::class,
        ];
    }
}
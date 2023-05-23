<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Program;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    const PROGRAMS = [
        ['La Reine Charlotte', "Promise au roi d’Angleterre contre son gré, Charlotte arrive à Londres et découvre que la famille royale n'est pas ce qu'elle imaginait. Le temps aidant, la jeune fille trouve ses marques au sein du palais, entre l'étiquette et son imprévisible mari. Malgré les difficultés, Charlotte est en passe de devenir l’une des monarques les plus incontournables d’Europe.", "Aventure", "queencharlotte"],
        ['The Mandalorian', "Après les histoires de Jango et Boba Fett, un autre guerrier émerge dans l'univers de Star Wars.
Le Mandalorien se situe après la chute de l'Empire et avant l'émergence du Premier Ordre.
Nous suivons les aventures du chasseur de primes isolé dans la bordure extérieure de la galaxie, loin de l'autorité de la Nouvelle République.", 'Science-fiction', "themandalorian"],
        ['Toujours là pour toi', "L'une est riche et célèbre. L'autre a l'amour et la famille. Ces amies de toujours sont aussi différentes que possible.", 'Comédie', "toujourslapourtoi"],
        ['Walking Dead', 'Le shérif Rick Grimes se réveille à l\'hopital après un long coma. Il découvre avec effarement que le monde, ravagé par une épidémie, est envahi par les morts-vivants.', 'Horreur', "walkingdead" ],
        ["The Queen's Gambit", "Dans les années 1950, une jeune orpheline passionnée d\'échecs lutte contre ses addictions en intégrant une école spécialisée.", 'Action', "queensgambit"]
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::PROGRAMS as $programUnique) {
                $program = new Program();
                $program->setTitle($programUnique[0]);
                $program->setSynopsis($programUnique[1]);
                $program->setCategory($this->getReference('category_' . $programUnique[2]));
                $program->setPoster($programUnique[3]);
                $manager->persist($program);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }
}

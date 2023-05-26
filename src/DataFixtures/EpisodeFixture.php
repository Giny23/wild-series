<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Episode;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class EpisodeFixture extends Fixture implements DependentFixtureInterface
{
    /*const EPISODES = [
        ['saison2_Walking Dead', 'Ce qui nous attend', '1', "Les survivants se retrouvent bloqués sur une route envahie par des carcasses de voitures. Ils décident d'en profiter pour siphonner les réservoirs. C'est alors qu'ils sont surpris par un groupe de zombies. Sophia disparait."],
        ['saison2_Walking Dead', 'Saignée', '2', "Otis, le chasseur, a indiqué à Rick que les habitants de la ferme pourraient sauver Carl, blessé par balles. Le policier, qui porte son fils inconscient, tente désespérément de rejoindre la ferme."]
    ];*/

    /**
     * @inheritDoc
     */
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    public function load(ObjectManager $manager)
    {
        /*foreach (self::EPISODES as $episodeUnique) {
            $episode = new Episode();
            $episode->setTitle($episodeUnique[1]);
            $episode->setNumber($episodeUnique[2]);
            $episode->setSynopsis($episodeUnique[3]);
            $episode->setSeason($this->getReference($episodeUnique[0]));
            $manager->persist($episode);
        }
        $manager->flush();*/

        $faker = Factory::create();

        for ($serie = 1; $serie < 6; $serie++) {
            for ($saison = 1; $saison < 6; $saison++) {
                for ($episodeNumber = 1; $episodeNumber < 11; $episodeNumber++) {
                    $episode = new Episode();
                    $episode->setTitle($faker->name());
                    $episode->setNumber($episodeNumber);
                    $episode->setSynopsis($faker->paragraphs(3, true));
                    $episode->setSeason($this->getReference('saison' . $saison . '_program_' . $serie));
                    $episode->setDuration($faker->numberBetween(10, 60));
                    $episode->setSlug($this->slugger->slug($episode->getTitle()));
                    $manager->persist($episode);
                }
            }
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            SeasonFixtures::class,
        ];
    }

}
<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CategoryFixtures extends Fixture
{
    const CATEGORIES = [
        'Horreur',
        'Action',
        'Aventure',
        'Comédie',
        'Fantastique',
        'Science-fiction',
    ];

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        foreach (self::CATEGORIES as $key => $category_name) {
            $category = new Category();
            $category->setName($category_name);
            $manager->persist($category);
        }
        $manager->flush();
    }
}
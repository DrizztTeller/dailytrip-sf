<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR'); // Chargement de Faker
        $categories = [
            'Désert',
            'Montagne',
            'Forêt',
            'Roadtrip',
            'Grotte',
            'Trail',
            'VTT',
            'Littoral',
            'Îles'
        ];

        for ($i = 0; $i < count($categories); $i++) {
            $category = new Category();
            $category
                ->setName($categories[$i])
                ->setImage('https://picsum.photos/300/300?random=' . $i)
            ;
            $this->addReference('category_' . $i, $category); // Ajoute une référence
            $manager->persist($category); // Ajoute à la BDD
        }
        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Faker\Factory;
use App\Entity\Trip;
use App\Entity\Localisation;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TripFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR'); // Chargement de Faker
        
        // Récupération des categories
        $categoryArray = [];
        for ($i=0; $i < 9; $i++) {
            $categoryArray[] = $this->getReference('category_' . $i, Category::class);
        }

        //  Les utilisateurs
        $userArray = [];
        for ($i=0; $i < 300; $i++) {
            $userArray[] = $this->getReference('user_' . $i, User::class);
        }
        
        for ($i = 0; $i < 1000; $i++) {
            // Création d'un localisation
            $localisation = new Localisation();
            $localisation
                ->setStart($faker->latitude() . ',' . $faker->longitude())
                ->setFinish($faker->latitude() . ',' . $faker->longitude())
                ->setDuration($faker->numerify('###'))
                ->setDistance($faker->numerify('###.###'))
            ;
            $manager->persist($localisation);

            // Création d'un trip
            $trip = new Trip();
            $trip
                ->setRef(uniqid()) // Génération d'un identifiant unique
                ->setTitle($faker->sentence(3))
                ->setDescription($faker->paragraph(4))
                ->setCover('https://picsum.photos/1280/720?random=' . $i)
                ->setEmail($faker->email())
                ->setStatus($faker->boolean(70))
                // Ici on utilise le tableau de categories pour en assigner à l'objet Trip
                ->setCategory($faker->randomElement($categoryArray))
                ->setLocalisation($localisation)
                ->setAuthor($faker->randomElement($userArray))
            ;

            $this->addReference('trip_' . $i, $trip); // Ajoute une référence
            $manager->persist($trip);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class, 
            UserFixtures::class
        ];
    }
}

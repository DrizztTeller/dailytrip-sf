<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Poi;
use App\Entity\Trip;
use App\Entity\Image;
use App\Entity\Gallery;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR'); // Chargement de Faker

        ini_set('memory_limit', '1024M');
        // Récupétration des Trips
        $tripArray = [];
        for ($i=0; $i < 300; $i++) {
            $tripArray[] = $this->getReference('trip_' . $i, Trip::class);
        }

        for ($i = 0; $i < 300; $i++) {
            $trip = $tripArray[$i];

            // Images 
            $imgArray = [];
            for ($l=0; $l < 3; $l++) {
                $img = new Image();
                $img
                    ->setUrl('https://picsum.photos/1280/720?random=' . $i + 1)
                    ->setUser($trip->getAuthor())
                    ;

                array_push($imgArray, $img);
                $manager->persist($img);
            }

            $poiArray = [];
            for ($j=0; $j < 3; $j++) {
                // Gallery
                $gal = new Gallery();
                for ($k=0; $k < count($imgArray); $k++) { 
                    $gal->addImage($imgArray[$k]);                
                }
                $manager->persist($gal);

                // POI
                $point= new Poi();
                $point
                    ->setPoint($faker->latitude() . ',' . $faker->longitude())
                    ->setGallery($gal)
                ;
                array_push($poiArray, $point);
                $manager->persist($point);
            }

            for ($m=0; $m < count($poiArray); $m++) { 
                $manager->persist($trip->getLocalisation()->addPoi($poiArray[$m]));
            }
        }

        $manager->flush(); // Exécute les requêtes SQL générées par Doctrine
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class, 
            TripFixtures::class
        ];
    }
}
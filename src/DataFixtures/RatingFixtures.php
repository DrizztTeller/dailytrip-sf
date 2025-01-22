<?php

namespace App\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;
use App\Entity\Trip;
use App\Entity\User;
use App\Entity\Rating;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class RatingFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $userArray = [];
        for ($i = 0; $i < 300; $i++) {
            $userArray[] = $this->getReference('user_' . $i, User::class);
        }

        $tripArray = [];
        for ($i = 0; $i < 1000; $i++) {
            $tripArray[] = $this->getReference('trip_' . $i, Trip::class);
        }

        for ($i = 0; $i < 500; $i++) {
            $user = $faker->randomElement($userArray);
            $rating = new Rating();
            $rating
                ->setNote($faker->numberBetween(1, 5))
                ->setIpAddress($faker->ipv4)
                ->setEvaluator($user)
                ->setTrip($faker->randomElement($tripArray));
            $manager->persist($rating);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            TripFixtures::class
        ];
    }
}

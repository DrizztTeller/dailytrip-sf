<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
    
        // Création des utilisateurs normaux
        for ($i = 0; $i < 300; $i++) {
            $user = new User();
            $user
                ->setUsername($faker->name)
                ->setEmail($faker->email)
                ->setPassword('$2y$13$rIVlC64Za/GIa4fm5/YMXuK6vKWAjPQ6J0F1DoQql2EP04tUUkvrW')
                ->setRoles(['ROLE_USER'])
                ->setVerified($faker->boolean(70))
            ;
    
            $this->addReference('user_' . $i, $user);
            $manager->persist($user);
        }
    
        // Création de l'admin
        $admin = new User();
        $admin
            ->setUsername('admin')
            ->setEmail('admin@gmail.com')
            ->setPassword('$2y$13$rIVlC64Za/GIa4fm5/YMXuK6vKWAjPQ6J0F1DoQql2EP04tUUkvrW')
            ->setRoles(['ROLE_ADMIN'])
        ;
        $this->addReference('user_admin', $admin); // Ajout d'une référence pour l'admin
        $manager->persist($admin);
    
        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture {
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher) {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager) {
        $faker = Factory::create();
        $user = new User($this->passwordHasher);
        $user->setMail($faker->email())
            ->setUsername($faker->name())
            ->setPassword($this->passwordHasher->hashPassword($user, "test"));

        $manager->persist($user);
        $manager->flush();
    }
}

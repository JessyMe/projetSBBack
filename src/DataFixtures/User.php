<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class User extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $user = new User();
        $user->setUsername();
        $user->setName();
        $user->setSurname();
        $user->setPassword();
        $user->setEmail();

        $manager->persist($user);
        $manager->flush();
    }
}

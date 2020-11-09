<?php

/*
 * This file is part of the Talent4tech project.
 * Olivier Toussaint <olivier@toussaint.fr>
 */

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        // $product = new Product();
        // $manager->persist($product);
        $user = new User();
        $user->setEmail('test@test.fr');
        $user->setName('Le personnage test');
        $user->setPassword($this->encoder->encodePassword($user, 'test'));
        $user->setConnectAt(new \DateTime('now'));
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);
        $manager->flush();

        for ($counter = 0; $counter < 10; ++$counter) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setName($faker->name);
            $user->setPassword($this->encoder->encodePassword($user, 'test'));
            $user->setConnectAt(new \DateTime('now'));
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
            $manager->flush();
        }
    }
}

<?php

/*
 * This file is part of the Talent4tech project.
 * Olivier Toussaint <olivier@toussaint.fr>
 */

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
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
            $user->setEmail(rand(1, 100).'@test.fr');
            $user->setName('name'.rand(1, 100));
            $user->setPassword($this->encoder->encodePassword($user, 'test'));
            $user->setConnectAt(new \DateTime('now'));
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
            $manager->flush();
        }
    }
}

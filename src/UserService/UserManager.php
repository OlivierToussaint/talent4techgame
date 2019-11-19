<?php

namespace App\UserService;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserManager
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function initUser(User $user)
    {
        $user->setAp(100);
        $user->setHp(100);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

}

<?php

/*
 * This file is part of the Talent4tech project.
 * Olivier Toussaint <olivier@toussaint.fr>
 */

namespace App\Service;

use App\Entity\User;

class UserAction
{
    private $userNotification;

    public function __construct(UserNotification $userNotification)
    {
        $this->userNotification = $userNotification;
    }

    public function canAttack(User $myCharacter, User $enemy): bool
    {
        if (User::DEAD === $enemy->getState()) {
            $this->userNotification->setMessage($enemy->getName().' est mort');

            return false;
        }
        if ($myCharacter->getAp() < User::ATTAQUE_COST) {
            $this->userNotification->setMessage("Vous n'avez plus assez de point d'action");

            return false;
        }

        return true;
    }

    public function attack(User $myCharacter, User $enemy): array
    {
        if (true === $this->canAttack($myCharacter, $enemy)) {
            // Point d'action
            $myCharacter->setAp($myCharacter->getAp() - User::ATTAQUE_COST);
            // Attaque
            $damage = random_int(1, 100);
            $hp = $enemy->getHp() - $damage;
            $enemy->setHp($hp);

            $this->userNotification->setMessage($myCharacter->getName().' attaque '.$enemy->getName().' pour '.$damage.' de dommage');

            if (User::DEAD === $enemy->getState()) {
                $this->userNotification->setMessage($enemy->getName().' est mort');
            }
        }

        return [$myCharacter, $enemy];
    }

    public function canHeal(User $myCharacter, User $enemy): bool
    {
        if (User::DEAD === $enemy->getState()) {
            $this->userNotification->setMessage($enemy->getName().' est mort');

            return false;
        }

        return true;
    }

    public function heal(User $myCharacter, User $friend)
    {
        if (true === $this->canHeal($myCharacter, $friend)) {
            // Point d'action
            $myCharacter->setAp($myCharacter->getAp() - User::HEAL_COST);

            // Heal
            $heal = rand(1, 50);
            $hp = $friend->getHp() + $heal;
            $friend->setHp($hp);

            $this->userNotification->setMessage($myCharacter->getName().' soigne '.$friend->getName().' pour '.$heal.' de soins');
        }

        return [$myCharacter, $friend];
    }
}

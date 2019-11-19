<?php

/*
 * This file is part of the Talent4tech project.
 * Olivier Toussaint <olivier@toussaint.fr>
 */

namespace App\UserService;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class UserNotification
{
    private $flashBag;

    public function __construct(FlashBagInterface $flashBag)
    {
        $this->flashBag = $flashBag;
    }

    public function setMessage(string $message): void
    {
        $this->flashBag->add('notification', $message);
    }
}

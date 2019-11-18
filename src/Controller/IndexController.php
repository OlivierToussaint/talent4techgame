<?php

/*
 * This file is part of the Talent4tech project.
 * Olivier Toussaint <olivier@toussaint.fr>
 */

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('index/index.html.twig');
    }

    /**
     * @Route("/game", name="game")
     */
    public function game(UserRepository $userRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $users = $userRepository->findAllWithoutMe($user);

        return $this->render('game/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/game/attack/{id}", name="attack")
     */
    public function attack(User $enemy, UserAction $userAction): Response
    {
        /** @var User $myCharacter */
        $myCharacter = $this->getUser();

        [$myCharacter, $enemy] = $userAction->attack($myCharacter, $enemy);

        if ($myCharacter instanceof User && $enemy instanceof User) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($myCharacter);
            $entityManager->persist($enemy);
            $entityManager->flush();
        }

        return $this->redirectToRoute('game');
    }

    /**
     * @Route("/game/heal/{id}", name="heal")
     */
    public function heal(User $friend, UserAction $userAction): Response
    {
        /** @var User $myCharacter */
        $myCharacter = $this->getUser();

        [$myCharacter, $friend] = $userAction->heal($myCharacter, $friend);

        if ($myCharacter instanceof User && $friend instanceof User) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($myCharacter);
            $entityManager->persist($friend);
            $entityManager->flush();
        }

        return $this->redirectToRoute('game');
    }
}

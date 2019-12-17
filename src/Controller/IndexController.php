<?php

/*
 * This file is part of the Talent4tech project.
 * Olivier Toussaint <olivier@toussaint.fr>
 */

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\UserService\UserAction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/game/attack/js", name="attack_js")
     */
    public function attackEnemyJs(Request $request, UserAction $userAction, UserRepository $userRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        $enemy = $userRepository->find((int) $data['attack']);
        if (!$enemy instanceof User) {
            throw new \RuntimeException('Pas de user');
        }

        /** @var User $myCharacter */
        $myCharacter = $this->getUser();

        [$myCharacter, $enemy] = $userAction->attack($myCharacter, $enemy);

        if ($myCharacter instanceof User && $enemy instanceof User) {
            $entityManager = $this->getDoctrine()->getManager();
            // Je mets à jour les points d'action de mon personnage
            $entityManager->persist($myCharacter);
            // Je mets à jour les points de vie de mon adversaire
            $entityManager->persist($enemy);
            $entityManager->flush();
        }

        return $this->json(['hp' => $enemy->getHp()]);
    }

    /**
     * @Route("/api/get/users", name="api_get_users")
     */
    public function getApiUsers(UserRepository $userRepository): Response
    {
        return $this->json($userRepository->findAll());
    }



    /**
     * @Route("/game/attack/{id}", name="attack")
     */
    public function attackEnemy(User $enemy, UserAction $userAction): Response
    {
        /** @var User $myCharacter */
        $myCharacter = $this->getUser();

        [$myCharacter, $enemy] = $userAction->attack($myCharacter, $enemy);

        if ($myCharacter instanceof User && $enemy instanceof User) {
            $entityManager = $this->getDoctrine()->getManager();
            // Je mets à jour les points d'action de mon personnage
            $entityManager->persist($myCharacter);
            // Je mets à jour les points de vie de mon adversaire
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

    /**
     * @Route("/api/test", name="api_test")
     */
    public function exempleApi()
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://randomuser.me/api/');

        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json'
        $content = $response->getContent();
        $test = json_decode($content);
        $html = "";
        foreach($test->results as $user) {
            $html .= $user->gender." ".$user->email."<br />";

        }


        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content = $response->toArray();
        return new Response($html);
    }
}

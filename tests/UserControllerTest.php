<?php

/*
 * This file is part of the Moto-Privee project.
 * Olivier Toussaint <olivier@toussaint.fr>
 */

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    public function testAdminUserList()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'johndoe@test.com',
            'PHP_AUTH_PW' => 'test',
        ]);
        $crawler = $client->request('GET', '/admin/user/list');
        $this->assertSame(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode(),
            sprintf('The %s public URL loads correctly.', '/admin/user/list')
        );
        $this->assertSame('Liste des clients', $crawler->filter('h2.header-title')->text());
    }

    public function testAdminUserEdit()
    {
        $newNameOfUser = 'User '.mt_rand();
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'johndoe@test.com',
            'PHP_AUTH_PW' => 'test',
        ]);

        $user = $client->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['email' => 'johndoe@test.com']);
        $this->assertNotNull($user, 'User dont exist');

        $crawler = $client->request('GET', '/admin/user/edit/'.$user->getId());

        $this->assertSame('Modification d\'un client', $crawler->filter('h2.header-title')->text());

        $form = $crawler->selectButton('Enregistrer')->form();

        $form['user_edit_admin[firstname]'] = $newNameOfUser;

        $client->submit($form);

        $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

        $user = $client->getContainer()->get('doctrine')->getRepository(User::class)->findOneBy(['email' => 'johndoe@test.com']);
        $this->assertSame($newNameOfUser, $user->getFirstName());
    }


}

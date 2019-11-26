<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testSomething()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Register');

        $form = $crawler->selectButton('S\'enregistrer')->form();
        // set some values
        $email = 'test@oliviert.fr' . rand();
        $form['registration_form[email]'] = $email;
        $form['registration_form[name]'] = 'Hey there!';
        $form['registration_form[plainPassword]'] = 'testtest';

        // submit the form
        $crawler = $client->submit($form);

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        $this->assertSame($email, $user->getEmail());

    }
}

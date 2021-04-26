<?php


namespace App\Tests;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;


class AbstractUsersTest extends ApiTestCase
{
    protected function createUser (string $email): User
    {
        $username = (substr($email, 0, strpos($email, '@')));
        $user = new User($username, $email);
        $user->setPassword('$2y$10$Bkmj5SVyHQ41pvj9WZK2RO5f6IWpGFU5jXvf85dB/5YWrtopr2JHK');
        $user->setSurname('test');
        $user->setName('Test');


        $em = self::$container->get(EntityManagerInterface::class);
        $em->persist($user);
        $em->flush();

        return $user;
    }

    protected function logIn (Client $client, string $email)
    {
        $client->request('POST', '/api/login', [
            'headers' => ['Content-Type'=> 'application/json'],
            'json'=> [
                "email"=> $email,
                "password" => 'password',
            ],
        ]);

        $this->assertResponseStatusCodeSame(200);
    }

    protected function createAndLogin (Client $client, string $email): User {

       $user = $this->createUser($email);
       $this->logIn($client, $email);

       $this->assertResponseStatusCodeSame(200);
       return $user;
    }
}
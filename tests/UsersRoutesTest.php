<?php


namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class UsersRoutesTest extends AbstractUsersTest

{
    use RefreshDatabaseTrait;

    public function testCreateUserRoute()
    {
        $client = self::createClient();
        $client->request('POST', '/api/users', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                "email" => "jessy5.mercer@hotmail.fr",
                "password" => '$2y$10$Bkmj5SVyHQ41pvj9WZK2RO5f6IWpGFU5jXvf85dB/5YWrtopr2JHK',
                "username" => "test5",
                "name" => "test",
                "surname" => "test"
            ],
        ]);
        $this->assertResponseStatusCodeSame(201);
    }

    public function testGetCollectionRoute() {

        $client = self::createClient();
        $this->createUser('testagain@gmail.com');
        $this->logIn($client, 'testagain@gmail.com');
    }
}
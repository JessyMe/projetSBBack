<?php


namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class UsersRoutesTest extends AbstractUsersTest

{


    public function testCreateUserRoute()
    {
        $client = self::createClient();
        $client->request('POST', '/api/users', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                "email" => "jessy90.mercer@hotmail.fr",
                "password" => 'password',
                "username" => "test90",
                "name" => "test",
                "surname" => "test"
            ],
        ]);
        $this->assertResponseStatusCodeSame(201);
    }

    public function testGetCollectionRoute() {

        $client = self::createClient();
        $this->createAndLogin($client, 'pleaaaaase@gmail.com');
        $client->request('GET', '/api/secure/users', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => []
        ]);
        $this->assertResponseIsSuccessful();
    }
}
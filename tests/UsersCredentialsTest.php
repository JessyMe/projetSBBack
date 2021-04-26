<?php


namespace App\Tests;


use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class UsersCredentialsTest extends AbstractTest
{
    use ReloadDatabaseTrait;

    public function testAdminResource()
    {
        $response = $this->createClientWithCredentials()->request('GET', '/api/secure/users');
        $this->assertResponseIsSuccessful();
    }

    public function testLoginAsUser()
    {
        $token = $this->getToken([
            'username'=> 'user2@exemple.com',
            'password' => '$2y$10$x7p8iKqdXgJfc6tAyt5yTOhhug4E1gtVoQRLb.A6YTwfqHFE9I6Gi',
        ]);

        $response = $this->createClientWithCredentials($token)->request('GET', '/api/secure/users');
        $this->assertJsonContains(['hydra:description' => 'Access Denied.']);
        $this->assertResponseStatusCodeSame('403');
    }
}
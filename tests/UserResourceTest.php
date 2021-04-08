<?php


namespace App\Tests;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshTestTrait;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use phpDocumentor\Reflection\Types\Static_;

class UserResourceTest extends ApiTestCase
{
    use ReloadDatabaseTrait;

    public function testLogin(): void
    {
        $client = self::createClient();
        $client->request('POST', '/api/login', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'jessy.mercer@gmail.com',
                'password' => 'password'
            ],
        ]);
        $this->assertResponseIsSuccessful();
        $this->testGetCollection();
    }

    public function testGetCollection() : void
    {
        $response = static::createClient()->request('GET', '/api/secure/users');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/User',
            '@id'=> '/api/users',
            '@type' => 'hydra:Collection',
            'hydra:totalItems'=> 1,
            'hydra:view'=> [
                '@id'=> 'api/users/',
                '@type'=> 'User',
                'hydra:first'=> '',
                'hydra:last' => '',
                'hydra:next'=> '',
            ],
        ]);

        $this->assertCount(1, $response->toArray()['hydra:member']);
        $this->assertMatchesResourceCollectionJsonSchema(User::class);
    }


}
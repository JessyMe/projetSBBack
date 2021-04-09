<?php


namespace App\Tests;


use App\Entity\User;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;


class UserResourceTest extends AbstractTest
{
    use ReloadDatabaseTrait;


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
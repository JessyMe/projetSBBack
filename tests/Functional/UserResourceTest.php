<?php


namespace App\Tests\Functional;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshTestTrait;
use phpDocumentor\Reflection\Types\Static_;

class UserResourceTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    public function testGetCollection() : void
    {
        $response = static::createClient()->request('GET', '/api/users');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testCreateUser()
    {
        $client = self::createClient();
        $this->assertResponseStatusCodeSame(401);
    }

}
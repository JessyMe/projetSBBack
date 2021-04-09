<?php


namespace App\Tests;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class UsersTest extends AbstractUsersTest
{

    public function testCreateAndLoginUser()
    {
        $client = self::createClient();
        $this->createUser('test7again@gmail.com');
        $this->logIn($client, 'test7again@gmail.com');
        $this->assertResponseStatusCodeSame(200);
    }

    public function testUpdateUserprofile()
    {
        $client = self::createClient();
        $user = $this->createUser('test7Updateagain@gmail.com');
        $this->logIn($client, 'test7Updateagain@gmail.com');
        $client->request('PUT', ' /api/secure/users/'.$user->getId(), [
        'json'=> ["name" => "newName"]
        ]);

        $this->assertResponseStatusCodeSame(200);
    }
}
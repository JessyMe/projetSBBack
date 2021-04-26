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
    public function testUpdateUserprofile()
    {
        $client = self::createClient();

        $user = $this->createAndLogin($client, 'Bilila@gmail.com');

        $id = $user->getId();
        $client->request('PUT', ' api/secure/users/' .$id , [
        'json'=> ['name' => 'newName']
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'name' => 'newName'
        ]);

    }
}
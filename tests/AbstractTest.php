<?php


namespace App\Tests;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use http\Client;

class AbstractTest extends ApiTestCase
{

    private $token;
    private $clientWithCredentials;

    use RefreshDatabaseTrait;

    public function setUp(): void
    {
        self::bootKernel();
    }

    protected function createClientWithCredentials($token = null): Client
    {
        $token = $token ?: $this->getToken();
        return static::createClient([], ['headers' => ['authorization' => 'Bearer'.$token]]);
    }

    protected function getToken($body = []): string
    {
        if($this->token) {
            return $this->token;
        }

        $response = static::createClient()->request('POST', '/api/login', ['body' => $body ?: [
            'email'=> 'user2@exemple.com',
            'password' => 'Pa$$w0rd',

        ]]);

        $this->assertResponseIsSuccessful();
        $data = json_decode($response->getContent());
        $this->token = $data->access_token;

        dd($data);
        return $data->access_token;
    }


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

    }
}
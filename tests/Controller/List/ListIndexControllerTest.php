<?php

namespace App\Tests\Controller\List;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ListIndexControllerTest extends WebTestCase
{
    public function testResponseIsSuccessful(): string
    {
        $client = static::createClient();
        $client->request('GET', '/list/');

        $this->assertResponseIsSuccessful();

        return $client->getResponse()->getContent();
    }

    /**
     * @depends testResponseIsSuccessful
     */
    public function testResponseIsJson(string $response): string
    {
        $this->assertJson($response);

        return $response;
    }

    /**
     * @depends testResponseIsJson
     */ 
    public function testJsonResponseSchema(string $response): void
    {
        $response = json_decode($response, true);

        $this->assertArrayHasKey('lists', $response);
    }
}

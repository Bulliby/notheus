<?php

namespace App\Tests\Controller\List;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class ListOneControllerTest extends WebTestCase
{
    public function testResponseIsSuccessful(): string
    {
        $client = static::createClient();

        $id = static::getContainer()->getParameter('api_constants.id.found');

        $client->request('GET', "/list/$id");
        $this->assertResponseIsSuccessful();

        return $client->getResponse()->getContent();
    }

    public function testResponseNotFound(): string
    {
        $client = static::createClient();

        $id = static::getContainer()->getParameter('api_constants.id.notFound');

        $client->request('GET', "/list/$id");
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        return $client->getResponse()->getContent();
    }

    /**
     * @depends testResponseIsSuccessful
     * @depends testResponseNotFound
     */
    public function testResponseIsJsonError(string $a, string $b): void
    {
        $this->assertJson($a);
        $this->assertJson($b);
    }

    /**
     * @depends testResponseIsSuccessful
     * @depends testResponseNotFound
     */ 
    public function JsonResponseSchema(string $a, string $b): void
    {
        $ar = json_decode($a, true);
        $br = json_decode($b, true);

        $this->assertSame($ar, $this->getParameter('api_constants.messages.success'));
        $this->assertSame($br, $this->getParameter('api_constants.messages.error'));
    }
}

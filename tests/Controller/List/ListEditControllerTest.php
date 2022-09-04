<?php

namespace App\Tests\Controller\List;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ListEditControllerTest extends WebTestCase
{
    public function testResponseIsSuccessful(): string
    {
        $client = static::createClient();

        $container = static::getContainer();
        $id = static::getContainer()->getParameter('api_constants.id.found');

        $client->jsonRequest('PUT', "/list/$id", [
            'name' => 'voila',
        ]);
        $this->assertResponseIsSuccessful();

        return $client->getResponse()->getContent();
    }

    public function testEmptyName(): string
    {
        $client = static::createClient();

        $container = static::getContainer();
        $id = static::getContainer()->getParameter('api_constants.id.found');

        $client->jsonRequest('PUT', "/project/list/$id/edit", 
            [
                'name' => ''
            ]
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        return $client->getResponse()->getContent();
    }

    public function testResponseNotFound(): string
    {
        $client = static::createClient();

        $container = static::getContainer();
        $id = static::getContainer()->getParameter('api_constants.id.notFound');
        $client->jsonRequest('PUT', "/list/$id", [
            'name' => 'voila',
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        return $client->getResponse()->getContent();
    }

    /**
     * @depends testResponseIsSuccessful
     * @depends testResponseNotFound
     * @depends testEmptyName
     */
    public function testResponseIsJson(string $a, string $b, string $c): void
    {
        $this->assertJson($a);
        $this->assertJson($b);
        $this->assertJson($c);
    }

    /**
     * @depends testResponseIsSuccessful
     * @depends testResponseNotFound
     * @depends testEmptyName
     */ 
    public function testJsonResponseSchema(string $a, string $b, string $c): void
    {
        $ar = json_decode($a, true);
        $br = json_decode($b, true);
        $cr = json_decode($c, true);
        $this->assertSame($ar, static::getContainer()->getParameter('api_constants.messages.success'));
        $this->assertSame($br, static::getContainer()->getParameter('api_constants.messages.error'));
        $this->assertSame($cr, static::getContainer()->getParameter('api_constants.messages.error'));
    }
}

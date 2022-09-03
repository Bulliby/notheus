<?php

namespace App\Tests\Controller\List;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\ListRepository;
use App\Interface\CraftedRequestException;
use Symfony\Component\HttpFoundation\Response;

class ListAddControllerTest extends WebTestCase
{
    public function testResponseIsSuccessful(): string
    {
        $client = static::createClient();

        $client->jsonRequest('POST', '/list/', [
            'name' => 'test add'
        ]);

        $this->assertResponseIsSuccessful();

        return $client->getResponse()->getContent();
    }

    public function testEmptyName(): string
    {
        $client = static::createClient();

        $client->jsonRequest('POST', '/list/', [
            'name' => ''
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        return $client->getResponse()->getContent();
    }

    /**
     * @depends testResponseIsSuccessful
     */
    public function testResponseIsJsonSuccess(string $a): void
    {
        $this->assertJson($a);
    }

    /**
     * @depends testResponseIsSuccessful
     */ 
    public function testJsonResponseSchemaSuccess(string $a): void
    {
        $ar = json_decode($a, true);

        $this->assertIsInt($ar);
    }
    /**
     * @depends testEmptyName
     */
    public function testResponseIsJsonError(string $a): void
    {
        $this->assertJson($a);
    }

    /**
     * @depends testEmptyName
     */ 
    public function testJsonResponseSchema(string $a): void
    {
        $ar = json_decode($a, true);

        $this->assertSame($ar, static::getContainer()->getParameter('api_constants.messages.error'));
    }

}

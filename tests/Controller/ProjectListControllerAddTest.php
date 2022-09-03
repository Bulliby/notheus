<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\ProjectListRepository;
use App\Const\RestControllerConst;
use App\Interface\CraftedRequestException;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\Response;

class ProjectListControllerAddTest extends WebTestCase
{
    public function testResponseIsSuccessful(): string
    {
        $client = static::createClient();
        self::bootKernel();

        $client->jsonRequest('POST', '/project/list/add', [
            'name' => 'test add'
        ]);

        $this->assertResponseIsSuccessful();

        return $client->getResponse()->getContent();
    }

    public function testEmptyName(): string
    {
        $client = static::createClient();
        self::bootKernel();

        $client->jsonRequest('POST', '/project/list/add', 
            [
                'name' => ''
            ]
        );

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

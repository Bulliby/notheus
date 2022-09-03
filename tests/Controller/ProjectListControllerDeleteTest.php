<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\ProjectListRepository;
use Symfony\Component\HttpFoundation\Response;


class ProjectListControllerDeleteTest extends WebTestCase
{
    public function testResponseIsSuccessful(): string
    {
        $client = static::createClient();
        self::bootKernel();
        $container = static::getContainer();

        $id = static::getContainer()->getParameter('api_constants.id.found');
        $client->request('DELETE', "/project/list/$id/delete");

        $this->assertResponseIsSuccessful();

        return $client->getResponse()->getContent();
    }

    public function testResponseNotFound(): string
    {
        $client = static::createClient();
        self::bootKernel();
        $container = static::getContainer();

        $id = static::getContainer()->getParameter('api_constants.id.notFound');
        $client->request('DELETE', "/project/list/$id/delete");

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

<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\ProjectListRepository;

class ProjectListControllerAddTest extends WebTestCase
{
    public function testResponseIsSuccessful(): string
    {
        $client = static::createClient();
        self::bootKernel();
        $container = static::getContainer();
        $projectListRepository = $container->get(ProjectListRepository::class);

        $client->jsonRequest('POST', '/project/list/add', ['id' => $projectListRepository->getAutoIcrementId() + 1, 'name' => 'test add']);

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
        $this->assertJsonStringEqualsJsonString('"Ok"', $response);
    }
}

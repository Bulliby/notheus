<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\ProjectListRepository;
use App\Const\RestControllerConst;

class ProjectListControllerOneTest extends WebTestCase
{
    public function testResponseIsSuccessful(): string
    {
        $client = static::createClient();
        self::bootKernel();
        $container = static::getContainer();
        $projectListRepository = $container->get(ProjectListRepository::class);

        //Here we get the autoincrement ID to be sure to not hit a 404
        $client->request('GET', '/project/list/'.$projectListRepository->getAutoIcrementId() - 1);

        $this->assertResponseIsSuccessful();

        return $client->getResponse()->getContent();
    }

    public function testResponseNotFound(): string
    {
        $client = static::createClient();
        self::bootKernel();
        $container = static::getContainer();
        $projectListRepository = $container->get(ProjectListRepository::class);

        //Here we get the autoincrement ID + 1 to be sure to hit a 404
        $id = $projectListRepository->getAutoIcrementId();
        $client->request('GET', '/project/list/'.$id);

        $this->assertResponseStatusCodeSame(RestControllerConst::ERROR_CODE);

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
     */ 
    public function JsonResponseSchema(string $a, string $b): void
    {
        $ar = json_decode($a, true);
        $br = json_decode($b, true);

        $this->assertSame($ar, RestControllerConst::SUCCESS_MESSAGE);
        $this->assertSame($br, RestControllerConst::ERROR_MESSAGE);
    }
}

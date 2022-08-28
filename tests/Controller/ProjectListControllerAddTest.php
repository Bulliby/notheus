<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\ProjectListRepository;
use App\Const\RestControllerConst;
use App\Interface\CustomExceptionInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ProjectListControllerAddTest extends WebTestCase
{
    public function testResponseIsSuccessful(): string
    {
        $client = static::createClient();
        self::bootKernel();
        $container = static::getContainer();
        $projectListRepository = $container->get(ProjectListRepository::class);

        $client->jsonRequest('POST', '/project/list/add', [
            'id' => $projectListRepository->getAutoIcrementId() + 1, 
            'name' => 'test add'
        ]);

        $this->assertResponseIsSuccessful();

        return $client->getResponse()->getContent();
    }

    public function testClientIdForbiden(): string
    {
        $client = static::createClient();
        self::bootKernel();
        $container = static::getContainer();
        $projectListRepository = $container->get(ProjectListRepository::class);

        $client->jsonRequest('POST', '/project/list/add', 
            [
                'id' => $projectListRepository->getAutoIcrementId() + 2, 
                'name' => 'test add'
            ]
        );

        $this->assertResponseStatusCodeSame(RestControllerConst::ERROR_CODE);

        return $client->getResponse()->getContent();
    }

    public function testEmptyName(): string
    {
        $client = static::createClient();
        self::bootKernel();
        $container = static::getContainer();
        $projectListRepository = $container->get(ProjectListRepository::class);

        $client->jsonRequest('POST', '/project/list/add', 
            [
                'id' => $projectListRepository->getAutoIcrementId() + 1, 
                'name' => ''
            ]
        );

        $this->assertResponseStatusCodeSame(RestControllerConst::ERROR_CODE);

        return $client->getResponse()->getContent();
    }

    /**
     * @depends testResponseIsSuccessful
     * @depends testClientIdForbiden
     * @depends testEmptyName
     */
    public function testResponseIsJsonError(string $a, string $b, string $c): void
    {
        $this->assertJson($a);
        $this->assertJson($b);
        $this->assertJson($c);
    }

    /**
     * @depends testResponseIsSuccessful
     * @depends testClientIdForbiden
     * @depends testEmptyName
     */ 
    public function testJsonResponseSchema(string $a, string $b, string $c): void
    {
        $ar = json_decode($a, true);
        $br = json_decode($b, true);
        $cr = json_decode($c, true);

        $this->assertSame($ar, RestControllerConst::SUCCESS_MESSAGE);
        $this->assertSame($br, RestControllerConst::ERROR_MESSAGE);
        $this->assertSame($cr, RestControllerConst::ERROR_MESSAGE);
    }

}

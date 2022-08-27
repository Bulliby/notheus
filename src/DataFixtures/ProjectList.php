<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\ProjectList as ProjectListEntity;

class ProjectList extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $list = new ProjectListEntity();
        $list->setName('Toto');
        $manager->persist($list);

        $list2 = new ProjectListEntity();
        $list2->setName('Tata');
        $manager->persist($list2);

        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\XList;

class ListFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $list = new XList();
        $list->setName('Toto');
        $manager->persist($list);

        $list2 = new XList();
        $list2->setName('Tata');
        $manager->persist($list2);

        $manager->flush();
    }
}

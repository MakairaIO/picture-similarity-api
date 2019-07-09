<?php

namespace App\DataFixtures;

use App\Entity\PictureSimilarity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $pictureSimPingDom = new PictureSimilarity();
        $pictureSimPingDom->setProductId('testproduct');
        $pictureSimPingDom->setSimilarIds(['its', 'the', 'pingdom', 'test']);
        $pictureSimPingDom->setShop('testshop');
        $pictureSimPingDom->setUpdatedAt(new \DateTime('2999-01-01 00:00:00'));

        $manager->persist($pictureSimPingDom);

        $manager->flush();
    }
}

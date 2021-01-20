<?php

namespace App\DataFixtures;

use App\Entity\PictureSimilarity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 5; $i++) {
            $imageTypeProduct = new PictureSimilarity();
            $imageTypeProduct->setProductId($i);
            $imageTypeProduct->setSimilarIds(['image', 'type', 'product', $i]);
            $imageTypeProduct->setShop('testshop');
            $imageTypeProduct->setType('image');
            $imageTypeProduct->setUpdatedAt(new \DateTime('2999-01-01 00:00:00'));
            $manager->persist($imageTypeProduct);

            $notImageTypeProduct = new PictureSimilarity();
            $notImageTypeProduct->setProductId($i);
            $notImageTypeProduct->setSimilarIds(['notImage', 'type', 'product', $i]);
            $notImageTypeProduct->setShop('testshop');
            $notImageTypeProduct->setType('notImage');
            $notImageTypeProduct->setUpdatedAt(new \DateTime('2999-01-01 00:00:00'));
            $manager->persist($notImageTypeProduct);
        }

        $manager->flush();
    }
}

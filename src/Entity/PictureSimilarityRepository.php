<?php

namespace App\Entity;

use Doctrine\ORM\EntityRepository;

class PictureSimilarityRepository extends EntityRepository
{
    public function getAvailableTypesByShop($shop): array
    {
        $queryBuilder = $this->createQueryBuilder('ps');
        $types = $queryBuilder->select('ps.type')
            ->where('ps.shop = :shop')
            ->setParameter('shop', $shop)
            ->groupBy('ps.type')
            ->getQuery()
            ->getResult();

        return array_column($types, 'type');
    }
}

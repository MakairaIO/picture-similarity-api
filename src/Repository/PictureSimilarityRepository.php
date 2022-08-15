<?php

namespace Makaira\PictureSimilarity\Repository;

use Doctrine\ORM\EntityRepository;

class PictureSimilarityRepository extends EntityRepository
{
    /**
     * @return mixed[]
     */
    public function getAvailableTypesByShop(string $shop): array
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

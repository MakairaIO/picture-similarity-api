<?php

namespace Makaira\PictureSimilarity\Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Makaira\PictureSimilarity\Entity\PictureSimilarity;

readonly class ListProvider
{
    private string $tableName;

    public function __construct(private Connection $connection, EntityManagerInterface $entityManager)
    {
        $this->tableName = $entityManager->getClassMetadata(PictureSimilarity::class)->getTableName();
    }

    /**
     * @return array
     * @throws DBALException
     */
    public function customerDatabases(): array
    {
        $result = $this->connection->executeQuery(
            "SELECT TABLE_SCHEMA FROM information_schema.TABLES WHERE TABLE_NAME = ?",
            [$this->tableName]
        );

        return $result->fetchFirstColumn();
    }
}

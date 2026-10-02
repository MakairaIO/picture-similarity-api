<?php

namespace Makaira\PictureSimilarity\Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DBALException;

readonly class ListProvider
{
    public function __construct(private Connection $connection)
    {
    }

    /**
     * @return array
     * @throws DBALException
     */
    public function customerDatabases(): array
    {
        $result = $this->connection->executeQuery(
            "SELECT TABLE_SCHEMA FROM information_schema.TABLES WHERE TABLE_NAME = 'picture_similarity'"
        );

        return $result->fetchFirstColumn();
    }
}

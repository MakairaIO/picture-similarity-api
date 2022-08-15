<?php

namespace Makaira\PictureSimilarity\Doctrine\DBAL;

use InvalidArgumentException;

use function in_array;
use function preg_replace;
use function strtolower;

trait DatabaseNameNormalizerTrait
{
    private static array $protectedDatabases = ['information_schema', 'mysql', 'sys', 'performance_schema'];

    public function normalize(string $dbName): string
    {
        if (in_array(strtolower($dbName), self::$protectedDatabases, true)) {
            throw new InvalidArgumentException(
                sprintf('The database name "%s" is a system database and can not be selected!', $dbName)
            );
        }

        return preg_replace('#\W#', '_', $dbName);
    }
}

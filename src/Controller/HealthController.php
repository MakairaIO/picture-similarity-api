<?php

namespace Makaira\PictureSimilarity\Controller;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

final readonly class HealthController
{
    /**
     * @param Connection $connection
     *
     * @return Response
     */
    #[Route(path: '/health')]
    public function check(Connection $connection): Response
    {
        try {
            $result = $connection->executeQuery(
                'SELECT COUNT(`SCHEMA_NAME`) FROM `information_schema`.`SCHEMATA` WHERE `SCHEMA_NAME` NOT IN (?)',
                [['mysql', 'performance_schema', 'information_schema', 'sys', 'testshop']],
                [ArrayParameterType::STRING]
            );
            $dbCount = $result->fetchOne();

            if (false !== $dbCount && 0 < $dbCount) {
                return $this->createResponse();
            }

            return $this->createResponse('No databases', Response::HTTP_NOT_FOUND);
        } catch (Throwable $t) {
            return $this->createResponse($t->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function createResponse(string $body = 'Healthy', int $status = 200): Response
    {
        return new Response($body, $status, ['Content-Type' => 'test/plain; charset=UTF-8']);
    }
}

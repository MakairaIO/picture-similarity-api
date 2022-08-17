<?php

namespace Makaira\PictureSimilarity\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

use function count;
use function in_array;

class HealthController
{
    private const SYSTEM_DATABASES = ['mysql', 'performance_schema', 'information_schema', 'sys'];

    /**
     * @param Connection $connection
     *
     * @return Response
     */
    #[Route(path: '/health', methods: ['GET'])]
    public function check(Connection $connection): Response
    {
        try {
            $result  = $connection->executeQuery('SHOW DATABASES');
            $dbNames = [];
            foreach ($result->iterateColumn() as $dbName) {
                if (!in_array($dbName, self::SYSTEM_DATABASES, true)) {
                    $dbNames[] = $dbName;
                }
            }

            if (count($dbNames) > 4) {
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

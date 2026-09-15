<?php

namespace Makaira\PictureSimilarity\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Makaira\PictureSimilarity\Doctrine\DBAL\MultiDbConnectionWrapper;
use Makaira\PictureSimilarity\Entity\PictureSimilarity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

use function array_merge;

#[Route(path: '/api')]
final readonly class PictureSimilarityController
{
    public function __construct(
        private ManagerRegistry $registry,
        private SerializerInterface $serializer,
    ) {
    }

    /**
     * Find similar products by shop and productId
     */
    #[Route(path: '/{shop}/{productId}', methods: ['GET'], priority: 10)]
    #[Route(path: '/{type}/{shop}/{productId}', methods: ['GET'], priority: 10)]
    public function find(string $shop, string $productId, string $type = 'image'): JsonResponse
    {
        $this->setDatabase($shop);

        $repository = $this->registry->getRepository(PictureSimilarity::class);
        $similar    = $repository->findBy(
            [
                'productId' => $productId,
                'shop'      => $shop,
                'type'      => $type === 'image' ? ['image', ''] : $type,
            ],
            ['updatedAt' => 'DESC'],
        );

        if ($similar === []) {
            throw new NotFoundHttpException('No Similar Products found.');
        }

        return $this->json($similar[0]);
    }

    /**
     * Find similar products by shop and productIds
     */
    #[Route(path: '/{shop}/products/{productIdList}', methods: ['GET'], priority: 20)]
    #[Route(path: '/{type}/{shop}/products/{productIdList}', methods: ['GET'], priority: 20)]
    public function findByProductIds(string $shop, string $productIdList, string $type = 'image'): JsonResponse
    {
        $productIds      = explode(',', $productIdList);
        $similarProducts = [];

        $this->setDatabase($shop);

        $repository = $this->registry->getRepository(PictureSimilarity::class);
        foreach ($productIds as $productId) {
            $similar = $repository->findBy(
                [
                    'productId' => $productId,
                    'shop'      => $shop,
                    'type'      => $type,
                ],
                ['updatedAt' => 'DESC'],
                1,
            );
            if (isset($similar[0])) {
                $similarProducts[] = array_map(static fn($s) => $s->getSimilarIds(), $similar);
            }
        }

        if (empty($similarProducts)) {
            throw new NotFoundHttpException('No Similar Products found.');
        }

        return $this->json(array_merge(...$similarProducts));
    }

    #[Route(path: '/{shop}/available-types', methods: ['GET'], priority: 30)]
    public function getAvailableTypes(string $shop): JsonResponse
    {
        $this->setDatabase($shop);

        $repository = $this->registry->getRepository(PictureSimilarity::class);

        return $this->json($repository->getAvailableTypesByShop($shop));
    }

    /**
     * Returns a JsonResponse that uses the serializer component if enabled, or json_encode.
     */
    protected function json($data, int $status = 200, array $headers = [], array $context = []): JsonResponse
    {
        $json = $this->serializer->serialize(
            $data,
            'json',
            array_merge(['json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS], $context),
        );

        return new JsonResponse($json, $status, $headers, true);
    }

    /**
     * @param string $shop
     *
     * @return void
     */
    private function setDatabase(string $shop): void
    {
        $connection = $this->registry->getConnection();
        if ($connection instanceof MultiDbConnectionWrapper) {
            $connection->selectDatabase($shop);
        }
    }

}

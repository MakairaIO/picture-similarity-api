<?php

namespace Makaira\PictureSimilarity\Controller;

use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Makaira\PictureSimilarity\Doctrine\DBAL\MultiDbConnectionWrapper;
use Makaira\PictureSimilarity\Entity\PictureSimilarity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

use function array_merge;

class PictureSimilarityController
{
    public function __construct(
        private readonly ManagerRegistry $registry,
        private readonly SerializerInterface $serializer
    ) {
    }

    /**
     * Find similar products by shop and productId
     */
    #[Route(path: '/{shop}/{productId}', methods: ['GET'])]
    #[Route(path: '/{type}/{shop}/{productId}', methods: ['GET'])]
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
            ['updatedAt' => 'DESC']
        );

        if ($similar === []) {
            throw new NotFoundHttpException('No Similar Products found.');
        }

        return $this->json($similar[0]);
    }

    /**
     * Find similar products by shop and productIds
     */
    #[Route(path: '/{shop}/products/{productIds}', methods: ['GET'])]
    #[Route(path: '/{type}/{shop}/products/{$productIdList}', methods: ['GET'])]
    public function findByProductIds(string $shop, string $productIdList, string $type = 'image'): JsonResponse
    {
        $productIds = explode(',', $productIdList);
        $similarProducts = [];

        $this->setDatabase($shop);

        $repository = $this->registry->getRepository(PictureSimilarity::class);
        foreach ($productIds as $productId) {
            $similar = $repository->findBy([
                'productId' => $productId,
                'shop'      => $shop,
                'type'      => $type,
            ], ['updatedAt' => 'DESC'], 1);
            if (isset($similar[0])) {
                $similarProducts[] = $similar[0];
            }
        }

        if (empty($similarProducts)) {
            throw new NotFoundHttpException('No Similar Products found.');
        }

        return $this->json($similarProducts);
    }

    #[Route(path: '{shop}/available-types', methods: ['GET'])]
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
            array_merge(['json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS], $context)
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
            try {
                $connection->selectDatabase($shop);
            } catch (Exception) {
            }
        }
    }

}

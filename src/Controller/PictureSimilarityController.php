<?php

namespace App\Controller;

use App\Entity\PictureSimilarity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class PictureSimilarityController extends AbstractController
{
    /**
     * Find similar products by shop and productId
     *
     * @param $shop
     * @param $productId
     * @param string $type
     * @return Response
     */
    public function find($shop, $productId, $type = 'image'): Response
    {
        $type = $type === 'image' ? array('image', '') : $type;

        $repository = $this->getDoctrine()->getRepository(PictureSimilarity::class);
        $similar = $repository->findBy([
            'productId' => $productId,
            'shop' => $shop,
            'type' => $type
        ], ['updatedAt' => 'DESC']);
        if (!$similar) {
            throw $this->createNotFoundException('No Similar Products found.');
        }

        return $this->json($similar[0]);
    }

    /**
     * Find similar products by shop and productIds
     *
     * @param $productIds
     * @param $shop
     * @param string $type
     * @return Response
     */
    public function findByProductIds($shop, $productIds, $type = 'image'): Response
    {
        $productIds = explode(',', $productIds);
        $similarProducts = [];

        $repository = $this->getDoctrine()->getRepository(PictureSimilarity::class);
        foreach ($productIds as $productId) {
            $similar = $repository->findBy([
                'productId' => $productId,
                'shop' => $shop,
                'type' => $type
            ], ['updatedAt' => 'DESC'], 1);
            if (isset($similar[0])) {
                $similarProducts[] = $similar[0];
            }
        }

        if (empty($similarProducts)) {
            throw $this->createNotFoundException('No Similar Products found.');
        }

        return $this->json($similarProducts);
    }
}

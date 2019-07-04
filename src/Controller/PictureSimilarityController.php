<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Entity\PictureSimilarity;

/**
 * PictureSimilarity controller
 */
class PictureSimilarityController extends AbstractController
{
    /**
     * Find similar products by shop and product_id
     *
     * @return JsonResponse
     */
    public function find($shop, $product_id)
    {
        $repository = $this->getDoctrine()->getRepository(PictureSimilarity::class);
        $similar = $repository->findBy(['productId' => $product_id, 'shop' => $shop], ['updatedAt' => 'DESC']);

        if (empty($similar)) {
            return new JsonResponse('', JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse($similar[0]);
    }

}
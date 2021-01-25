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
        $bindParameters = [
            'shop' => $shop,
        ];

        // generate productId placeholders and parameters
        $productIds = explode(',', $productIds);
        $productIdParameterPlaceholders = [];
        $i = 0;
        foreach ($productIds as $productId) {
            $i++;
            $productIdParameterPlaceholders[] = ':productId' . $i;
            $bindParameters['productId' . $i] = $productId;
        }
        $productIdParameterPlaceholders = implode(',', $productIdParameterPlaceholders);

        // generate type placeholders and parameters
        if ($type === 'image') {
            $typeParameterPlaceholders = ':type1, :type2';
            $bindParameters['type1'] = 'image';
            $bindParameters['type2'] = '';
        } else {
            $typeParameterPlaceholders = ':type';
            $bindParameters['type'] = $type;
        }

        // get data from DB
        $conn = $this->getDoctrine()->getConnection();
        $sql = "SELECT ps.similar_ids 
            FROM picture_similarity AS ps 
            JOIN (SELECT product_id, MAX(updated_at) updated_at FROM picture_similarity GROUP BY product_id) AS sps 
                ON sps.product_id = ps.product_id AND sps.updated_at = ps.updated_at 
            WHERE ps.product_id IN ({$productIdParameterPlaceholders}) 
              AND ps.shop = :shop 
              AND ps.type IN ({$typeParameterPlaceholders})";
        $stmt = $conn->prepare($sql);
        $stmt->execute($bindParameters);
        $similarProducts = $stmt->fetchAll(\PDO::FETCH_COLUMN);

        if (!$similarProducts) {
            throw $this->createNotFoundException('No Similar Products found.');
        }

        $result = [];
        foreach ($similarProducts as $similarIds) {
            $result[] = json_decode($similarIds);
        }
        return $this->json($result);
    }
}

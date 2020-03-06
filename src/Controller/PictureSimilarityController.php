<?php
namespace App\Controller;

use App\Entity\PictureSimilarity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class PictureSimilarityController extends Controller
{
    /**
     * Find similar products by shop and productId
     *
     * @return Response
     */
    public function find($type = 'image', $shop, $productId)
    {
        $repository = $this->getDoctrine()->getRepository(PictureSimilarity::class);
        $similar = $repository->findBy(['productId' => $productId, 'shop' => $shop, 'type' => $type], ['updatedAt' => 'DESC']);
        if (!$similar) {
            throw $this->createNotFoundException('No Similar Products found.');
        }

        return $this->json($similar[0]);
    }
}

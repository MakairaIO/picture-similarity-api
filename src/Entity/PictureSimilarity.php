<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="picture_similarity",indexes={@Index(name="search_idx", columns={"product_id", "shop"})})
 */
class PictureSimilarity implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, name="product_id")
     * @Assert\NotBlank()
     */
    private $productId;

    /**
     * @ORM\Column(type="json", name="similar_ids")
     * @Assert\NotBlank()
     *
     */
    private $similarIds;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $shop;

    /**
     * @ORM\Column(type="datetime", name="updated_at")
     */
    private $updatedAt;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @param mixed $productId
     */
    public function setProductId($productId): void
    {
        $this->productId = $productId;
    }

    /**
     * @return mixed
     */
    public function getSimilarIds()
    {
        return $this->similarIds;
    }

    /**
     * @param mixed $similarIds
     */
    public function setSimilarIds($similarIds): void
    {
        $this->similarIds = $similarIds;
    }

    /**
     * @return mixed
     */
    public function getShop()
    {
        return $this->shop;
    }

    /**
     * @param mixed $shop
     */
    public function setShop($shop): void
    {
        $this->shop = $shop;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function jsonSerialize()
    {
        return $this->getSimilarIds();
    }


}

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

use JSONSerializable;

/**
 * @ORM\Entity
 * @ORM\Table(name="picture_similarity",indexes={@Index(name="search_idx", columns={"product_id", "shop"})})
 */
class PictureSimilarity implements JSONSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, name="product_id")
     */
    private $productId;

    /**
     * @ORM\Column(type="json", name="similar_ids")
     */
    private $similarIds = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $shop;

    /**
     * @ORM\Column(type="datetime", name="updated_at")
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductId(): ?string
    {
        return $this->productId;
    }

    public function setProductId(string $productId): self
    {
        $this->productId = $productId;

        return $this;
    }

    public function getSimilarIds(): ?array
    {
        return $this->similarIds;
    }

    public function setSimilarIds(array $similarIds): self
    {
        $this->similarIds = $similarIds;

        return $this;
    }

    public function getShop(): ?string
    {
        return $this->shop;
    }

    public function setShop(string $shop): self
    {
        $this->shop = $shop;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function jsonSerialize()
    {
        return $this->getSimilarIds();
    }
}

<?php

namespace Makaira\PictureSimilarity\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

use JsonSerializable;
use Makaira\PictureSimilarity\Repository\PictureSimilarityRepository;

#[ORM\Entity(repositoryClass: PictureSimilarityRepository::class)]
#[ORM\Table]
#[ORM\Index('updated_at', columns: ['updated_at'])]
#[ORM\UniqueConstraint(columns: ['type', 'shop', 'product_id'])]
class PictureSimilarity implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $productId = null;

    #[ORM\Column(type: 'json')]
    private array $similarIds = [];

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $shop = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $type;

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

    /**
     * @return array
     */
    public function getSimilarIds(): array
    {
        return $this->similarIds;
    }

    /**
     * @param array $similarIds
     *
     * @return PictureSimilarity
     */
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

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->getSimilarIds();
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}

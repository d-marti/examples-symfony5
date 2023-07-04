<?php

namespace DMarti\ExamplesSymfony5\Entity;

use DateTimeImmutable;
use DMarti\ExamplesSymfony5\Repository\CustomerOrderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerOrderRepository::class)]
class CustomerOrder
{
    /** @var int */
    public const STATUS_FULFILLMENT_PENDING = 1;
    /** @var int */
    public const STATUS_FULFILLMENT_PACKED = 2;
    /** @var int */
    public const STATUS_FULFILLMENT_DELIVERED = 3;
    /** @var int */
    public const STATUS_FULFILLMENT_CANCELLED = 4;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    private int $statusFulfillment = self::STATUS_FULFILLMENT_PENDING;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $fulfilledAt = null;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatusFulfillment(): int
    {
        return $this->statusFulfillment;
    }

    public function setStatusFulfillment(int $statusFulfillment): static
    {
        $this->statusFulfillment = $statusFulfillment;

        return $this;
    }

    public function getFulfilledAt(): ?DateTimeImmutable
    {
        return $this->fulfilledAt;
    }

    public function setFulfilledAt(?DateTimeImmutable $fulfilledAt): static
    {
        $this->fulfilledAt = $fulfilledAt;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}

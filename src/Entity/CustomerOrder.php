<?php

namespace DMarti\ExamplesSymfony5\Entity;

use DateTimeImmutable;
use DMarti\ExamplesSymfony5\Repository\CustomerOrderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: CustomerOrderRepository::class)]
class CustomerOrder
{
    use TimestampableEntity;

    /** @var int */
    public const STATUS_FULFILLMENT_PENDING = 1;
    /** @var int */
    public const STATUS_FULFILLMENT_PACKED = 2;
    /** @var int */
    public const STATUS_FULFILLMENT_DELIVERED = 3;
    /** @var int */
    public const STATUS_FULFILLMENT_CANCELLED = 4;
    /** @var array */
    public const STATUS_FULFILLMENT_TEXTS = [
        self::STATUS_FULFILLMENT_PENDING => 'Pending',
        self::STATUS_FULFILLMENT_PACKED => 'Packed',
        self::STATUS_FULFILLMENT_DELIVERED => 'Delivered',
        self::STATUS_FULFILLMENT_CANCELLED => 'Cancelled',
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT, options: ['unsigned' => true])]
    private int $statusFulfillment = self::STATUS_FULFILLMENT_PENDING;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $fulfilledAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatusFulfillment(): int
    {
        return $this->statusFulfillment;
    }

    public function getStatusFulfillmentText(): string
    {
        return self::STATUS_FULFILLMENT_TEXTS[$this->statusFulfillment] ?? '';
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

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'statusFulfillment' => $this->statusFulfillment,
            'statusFulfillmentText' => $this->getStatusFulfillmentText(),
            'fulfilledAt' => ($this->fulfilledAt ? $this->fulfilledAt->format('Y-m-d H:i:s') : null),
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}

<?php

namespace DMarti\ExamplesSymfony5\Entity;

use DMarti\ExamplesSymfony5\Repository\CustomerOrderProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerOrderProductRepository::class)]
class CustomerOrderProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $quantityOrdered = null;

    // Adding the default for the column is not necessary, since we set it in our code.
    #[ORM\Column(type: Types::SMALLINT, options: ['default' => 0])]
    private int $quantityPacked = 0;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CustomerOrder $customerOrder = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantityOrdered(): ?int
    {
        return $this->quantityOrdered;
    }

    public function setQuantityOrdered(int $quantityOrdered): static
    {
        $this->quantityOrdered = $quantityOrdered;

        return $this;
    }

    public function getQuantityPacked(): int
    {
        return $this->quantityPacked;
    }

    public function setQuantityPacked(int $quantityPacked): static
    {
        $this->quantityPacked = $quantityPacked;

        return $this;
    }

    public function getCustomerOrder(): ?CustomerOrder
    {
        return $this->customerOrder;
    }

    public function setCustomerOrder(?CustomerOrder $customerOrder): static
    {
        $this->customerOrder = $customerOrder;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'productId' => $this->product->getId(),
            'productLabel' => $this->product->getLabel(),
            'quantityOrdered' => $this->getQuantityOrdered(),
            'quantityPacked' => $this->getQuantityPacked(),
        ];
    }
}

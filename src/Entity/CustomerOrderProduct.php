<?php

namespace DMarti\ExamplesSymfony5\Entity;

use DMarti\ExamplesSymfony5\Constant\DbForeignKeyAction;
use DMarti\ExamplesSymfony5\Repository\CustomerOrderProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerOrderProductRepository::class)]
#[ORM\UniqueConstraint(name: 'customer_order_id_product_id', columns: ['customer_order_id', 'product_id'])]
class CustomerOrderProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\Column(nullable: false, type: Types::SMALLINT)]
    private ?int $quantityOrdered = null;

    #[ORM\Column(nullable: false, type: Types::SMALLINT)]
    private ?int $quantityToPack = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    // It would have been nice if these DB keywords would be defined as constants in DBAL
    // So I made one for foreign key actions: DbForeignKeyAction
    #[ORM\JoinColumn(nullable: false, onDelete: DbForeignKeyAction::CASCADE)]
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

    public function getQuantityToPack(): int
    {
        return $this->quantityToPack;
    }

    public function setQuantityToPack(int $quantityToPack): static
    {
        $this->quantityToPack = $quantityToPack;

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
            // The moment you try to get more details than just the ID (which we have in our table)
            // an extra select query will be made. To avoid this, especially when getting multiple
            // customer order products (see CustomerOrder::getProducts), write a function with JOIN
            // clauses in the corresponding Repository. See: CustomerOrderProductRepository::findAllNotPackedByOrderId
            'productLabel' => $this->product->getLabel(),
            'quantityOrdered' => $this->getQuantityOrdered(),
            'quantityToPack' => $this->getQuantityToPack(),
        ];
    }
}

<?php

namespace DMarti\ExamplesSymfony5\Entity;

use DateTime;
use DMarti\ExamplesSymfony5\Constant\CustomerOrderStatusFulfillment;
use DMarti\ExamplesSymfony5\Repository\CustomerOrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: CustomerOrderRepository::class)]
class CustomerOrder
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    // I've added unsinged here to give us more possible IDs.
    // You can add unsigned to any int columns but it's not needed,
    // especially when working with small status-like numbers.
    #[ORM\Column(options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 10, enumType: CustomerOrderStatusFulfillment::class)]
    private CustomerOrderStatusFulfillment $statusFulfillment = CustomerOrderStatusFulfillment::Pending;

    #[ORM\Column(nullable: true)]
    private ?DateTime $fulfilledAt = null;

    #[ORM\OneToMany(
        mappedBy: 'customerOrder',
        targetEntity: CustomerOrderProduct::class,
        orphanRemoval: true,
        fetch: 'EXTRA_LAZY'
    )]
    // You can also add an OrderBy. In this case it would order by Product ID:
    //#[ORM\OrderBy(['product' => 'ASC'])]
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatusFulfillment(): CustomerOrderStatusFulfillment
    {
        return $this->statusFulfillment;
    }

    public function setStatusFulfillment(CustomerOrderStatusFulfillment $statusFulfillment): static
    {
        $this->statusFulfillment = $statusFulfillment;

        return $this;
    }

    public function getFulfilledAt(): ?DateTime
    {
        return $this->fulfilledAt;
    }

    public function setFulfilledAt(?DateTime $fulfilledAt): static
    {
        $this->fulfilledAt = $fulfilledAt;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'statusFulfillment' => $this->statusFulfillment,
            'fulfilledAt' => ($this->fulfilledAt ? $this->fulfilledAt->format('Y-m-d H:i:s') : null),
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt->format('Y-m-d H:i:s')
        ];
    }

    /**
     * @return Collection<int, CustomerOrderProduct>|ArrayCollection<int, CustomerOrderProduct>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * @return Collection<int, CustomerOrderProduct>|ArrayCollection<int, CustomerOrderProduct>
     */
    public function getNotPackedProducts(): Collection
    {
        return $this->products->filter(function (CustomerOrderProduct $product) {
            return ($product->getQuantityPacked() < $product->getQuantityOrdered());
        });
    }

    public function addCustomerOrderProduct(CustomerOrderProduct $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setCustomerOrder($this);
        }

        return $this;
    }

    public function removeCustomerOrderProduct(CustomerOrderProduct $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCustomerOrder() === $this) {
                $product->setCustomerOrder(null);
            }
        }

        return $this;
    }
}

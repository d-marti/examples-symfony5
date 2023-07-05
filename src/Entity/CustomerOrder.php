<?php

namespace DMarti\ExamplesSymfony5\Entity;

use DateTimeImmutable;
use DMarti\ExamplesSymfony5\Constant\CustomerOrderStatusFulfillment;
use DMarti\ExamplesSymfony5\Repository\CustomerOrderProductRepository;
use DMarti\ExamplesSymfony5\Repository\CustomerOrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CustomerOrderRepository::class)]
class CustomerOrder
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups('customerOrder:read')]
    // I've added unsinged here to give us more possible IDs.
    // You can add unsigned to any int columns but it's not needed,
    // especially when working with small status-like numbers.
    #[ORM\Column(options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 10, enumType: CustomerOrderStatusFulfillment::class)]
    #[Groups('customerOrder:read')]
    private CustomerOrderStatusFulfillment $statusFulfillment = CustomerOrderStatusFulfillment::Pending;

    #[ORM\Column(nullable: true, type: Types::DATETIME_IMMUTABLE)]
    #[Groups('customerOrder:read')]
    private ?DateTimeImmutable $fulfilledAt = null;

    #[ORM\OneToMany(
        mappedBy: 'customerOrder',
        targetEntity: CustomerOrderProduct::class,
        orphanRemoval: true,
        fetch: 'EXTRA_LAZY'
    )]
    // You can also add an OrderBy. In this case it would order by Product ID:
    //#[ORM\OrderBy(['product' => 'ASC'])]
    /**
     * @var Collection<int, CustomerOrderProduct>|ArrayCollection<int, CustomerOrderProduct>
     */
    private Collection|ArrayCollection $products;

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

    public function getFulfilledAt(): ?DateTimeImmutable
    {
        return $this->fulfilledAt;
    }

    public function setFulfilledAt(?DateTimeImmutable $fulfilledAt): static
    {
        $this->fulfilledAt = $fulfilledAt;

        return $this;
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
        // Instead of getting all products, looping over them, and filtering out some of them,
        // we're going to use a Criteria to add a where clause directly into our select query.
        return $this->products->matching(CustomerOrderProductRepository::criteriaNotPacked());
        /*
        return $this->products->filter(function (CustomerOrderProduct $product) {
            return ($product->getQuantityToPack() > 0);
        });
        */
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

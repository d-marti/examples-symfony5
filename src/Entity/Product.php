<?php

namespace DMarti\ExamplesSymfony5\Entity;

use DMarti\ExamplesSymfony5\Constant\ProductMetricType;
use DMarti\ExamplesSymfony5\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Slug;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    private ?string $label = null;

    #[ORM\Column(length: 100, unique: true)]
    #[Slug(fields: ['label'])]
    private ?string $slug = null;

    #[ORM\Column(options: ['default' => 0])]
    private bool $isMedical = false;

    #[ORM\Column(nullable: true)]
    private ?float $weight = null;

    #[ORM\Column(nullable: true)]
    private ?float $metricValue = null;

    #[ORM\Column(nullable: true, type: Types::STRING, length: 10, enumType: ProductMetricType::class)]
    private ?ProductMetricType $metricType = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function isMedical(): bool
    {
        return $this->isMedical;
    }

    public function setIsMedical(bool $isMedical): static
    {
        $this->isMedical = $isMedical;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getMetricValue(): ?float
    {
        return $this->metricValue;
    }

    public function setMetricValue(?float $metricValue): static
    {
        $this->metricValue = $metricValue;

        return $this;
    }

    public function getMetricType(): ?ProductMetricType
    {
        return $this->metricType;
    }

    public function setMetricType(?ProductMetricType $metricType): static
    {
        $this->metricType = $metricType;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
}

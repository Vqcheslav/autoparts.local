<?php

namespace App\Entity;

use App\Repository\AutopartRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AutopartRepository::class)]
class Autopart
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    private ?string $autopartId = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 2000)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'autopart', targetEntity: Order::class, orphanRemoval: true)]
    private Collection $orders;

    #[ORM\ManyToOne(inversedBy: 'autoparts')]
    #[ORM\JoinColumn(referencedColumnName: 'car_id', nullable: false)]
    private ?Car $car = null;

    #[ORM\ManyToOne(inversedBy: 'autoparts')]
    #[ORM\JoinColumn(referencedColumnName: 'warehouse_id', nullable: false)]
    private ?Warehouse $warehouse = null;

    #[ORM\Column]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'autoparts')]
    #[ORM\JoinColumn(referencedColumnName: 'manufacturer_id', nullable: false)]
    private ?Manufacturer $manufacturer = null;

    public function __construct()
    {
        $this->autopartId = uuid_create();
        $this->setCreatedAt(new DateTimeImmutable());
        $this->setUpdatedAtNow();
        $this->orders = new ArrayCollection();
    }

    public function getAutopartId(): ?string
    {
        return $this->autopartId;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->setUpdatedAtNow();
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->setUpdatedAtNow();
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setAutopart($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getAutopart() === $this) {
                $order->setAutopart(null);
            }
        }

        return $this;
    }

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): self
    {
        $this->setUpdatedAtNow();
        $this->car = $car;

        return $this;
    }

    public function getWarehouse(): ?Warehouse
    {
        return $this->warehouse;
    }

    public function setWarehouse(?Warehouse $warehouse): self
    {
        $this->setUpdatedAtNow();
        $this->warehouse = $warehouse;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function setUpdatedAtNow(): self
    {
        $this->setUpdatedAt(new DateTimeImmutable());

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->setUpdatedAtNow();
        $this->createdAt = $createdAt;

        return $this;
    }

    public function __toString(): string
    {
        return $this->title;
    }

    public function getManufacturer(): ?Manufacturer
    {
        return $this->manufacturer;
    }

    public function setManufacturer(?Manufacturer $manufacturer): static
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\WarehouseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WarehouseRepository::class)]
class Warehouse
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    private ?string $warehouseId = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    private ?string $openingHours = null;

    #[ORM\OneToMany(mappedBy: 'warehouse', targetEntity: Autopart::class)]
    private Collection $autoparts;

    #[ORM\Column(length: 255)]
    private ?string $workingDays = null;

    #[ORM\Column(length: 255)]
    private ?string $phoneNumber = null;

    public function __construct()
    {
        $this->warehouseId = uuid_create();
        $this->autoparts = new ArrayCollection();
    }

    public function getWarehouseId(): ?string
    {
        return $this->warehouseId;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getOpeningHours(): ?string
    {
        return $this->openingHours;
    }

    public function setOpeningHours(string $openingHours): self
    {
        $this->openingHours = $openingHours;

        return $this;
    }

    /**
     * @return Collection<int, Autopart>
     */
    public function getAutoparts(): Collection
    {
        return $this->autoparts;
    }

    public function addAutopart(Autopart $autopart): self
    {
        if (!$this->autoparts->contains($autopart)) {
            $this->autoparts->add($autopart);
            $autopart->setWarehouse($this);
        }

        return $this;
    }

    public function removeAutopart(Autopart $autopart): self
    {
        if ($this->autoparts->removeElement($autopart)) {
            // set the owning side to null (unless already changed)
            if ($autopart->getWarehouse() === $this) {
                $autopart->setWarehouse(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->address;
    }

    public function getWorkingDays(): ?string
    {
        return $this->workingDays;
    }

    public function setWorkingDays(string $workingDays): self
    {
        $this->workingDays = $workingDays;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }
}

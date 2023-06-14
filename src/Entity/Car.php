<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    private ?string $carId = null;

    #[ORM\Column(length: 255)]
    private ?string $manufacturer = null;

    #[ORM\Column(length: 255)]
    private ?string $brand = null;

    #[ORM\Column(length: 255)]
    private ?string $model = null;

    #[ORM\Column(length: 255)]
    private ?string $generation = null;

    #[ORM\Column(length: 255)]
    private ?string $engine = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $year = null;

    #[ORM\OneToMany(mappedBy: 'car', targetEntity: Autopart::class, orphanRemoval: true)]
    private Collection $autoparts;

    public function __construct()
    {
        $this->carId = uuid_create();
        $this->autoparts = new ArrayCollection();
    }

    public function getCarId(): ?string
    {
        return $this->carId;
    }

    public function getManufacturer(): ?string
    {
        return $this->manufacturer;
    }

    public function setManufacturer(string $manufacturer): self
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getGeneration(): ?string
    {
        return $this->generation;
    }

    public function setGeneration(string $generation): self
    {
        $this->generation = $generation;

        return $this;
    }

    public function getEngine(): ?string
    {
        return $this->engine;
    }

    public function setEngine(string $engine): self
    {
        $this->engine = $engine;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

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
            $autopart->setCar($this);
        }

        return $this;
    }

    public function removeAutopart(Autopart $autopart): self
    {
        if ($this->autoparts->removeElement($autopart)) {
            // set the owning side to null (unless already changed)
            if ($autopart->getCar() === $this) {
                $autopart->setCar(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return sprintf(
            '%s %s %s %s',
            $this->getBrand(),
            $this->getModel(),
            $this->getGeneration(),
            $this->getEngine()
        );
    }
}

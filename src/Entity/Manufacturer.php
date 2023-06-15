<?php

namespace App\Entity;

use App\Repository\ManufacturerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ManufacturerRepository::class)]
class Manufacturer
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    private ?string $manufacturerId = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 2)]
    private ?string $country = null;

    #[ORM\OneToMany(mappedBy: 'manufacturer', targetEntity: Autopart::class, orphanRemoval: true)]
    private Collection $autoparts;

    public function __construct()
    {
        $this->manufacturerId = uuid_create();
        $this->autoparts = new ArrayCollection();
    }

    public function getManufacturerId(): ?string
    {
        return $this->manufacturerId;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection<int, Autopart>
     */
    public function getAutoparts(): Collection
    {
        return $this->autoparts;
    }

    public function addAutopart(Autopart $autopart): static
    {
        if (!$this->autoparts->contains($autopart)) {
            $this->autoparts->add($autopart);
            $autopart->setManufacturer($this);
        }

        return $this;
    }

    public function removeAutopart(Autopart $autopart): static
    {
        if ($this->autoparts->removeElement($autopart)) {
            // set the owning side to null (unless already changed)
            if ($autopart->getManufacturer() === $this) {
                $autopart->setManufacturer(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return sprintf('%s - %s', $this->title, $this->country);
    }
}

<?php

namespace App\Entity;

use App\Repository\AutopartRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AutopartRepository::class)]
class Autopart
{
    public const DEFAULT_IMAGE_PATH = '/img/components/gearshift.svg';

    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    #[Groups(['show'])]
    private ?string $autopartId = null;

    #[ORM\Column(length: 255)]
    #[Groups(['show'])]
    private ?string $title = null;

    #[ORM\Column(length: 2000)]
    #[Groups(['show'])]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'autopart', targetEntity: AutopartOrder::class, orphanRemoval: true)]
    private Collection $orders;

    #[ORM\ManyToOne(inversedBy: 'autoparts')]
    #[ORM\JoinColumn(referencedColumnName: 'car_id', nullable: false)]
    #[Groups(['show'])]
    private ?Car $car = null;

    #[ORM\ManyToOne(inversedBy: 'autoparts')]
    #[ORM\JoinColumn(referencedColumnName: 'warehouse_id', nullable: false)]
    #[Groups(['show'])]
    private ?Warehouse $warehouse = null;

    #[ORM\Column]
    #[Groups(['show'])]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    #[Groups(['show'])]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'autoparts')]
    #[ORM\JoinColumn(referencedColumnName: 'manufacturer_id', nullable: false)]
    #[Groups(['show'])]
    private ?Manufacturer $manufacturer = null;

    #[ORM\OneToMany(mappedBy: 'autopart', targetEntity: Favorite::class, orphanRemoval: true)]
    private Collection $favorites;

    #[ORM\OneToMany(mappedBy: 'autopart', targetEntity: Cart::class, orphanRemoval: true)]
    private Collection $carts;

    #[ORM\Column(length: 255)]
    private ?string $imagePath = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column(length: 3)]
    private ?string $currency = null;

    public function __construct()
    {
        $this->autopartId = uuid_create();
        $this->setCreatedAt(new DateTimeImmutable());
        $this->setImagePath(self::DEFAULT_IMAGE_PATH);
        $this->setUpdatedAtNow();
        $this->orders = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->carts = new ArrayCollection();
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
     * @return Collection<int, AutopartOrder>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(AutopartOrder $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setAutopart($this);
        }

        return $this;
    }

    public function removeOrder(AutopartOrder $order): self
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

    /**
     * @return Collection<int, Favorite>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Favorite $favorite): static
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites->add($favorite);
            $favorite->setAutopart($this);
        }

        return $this;
    }

    public function removeFavorite(Favorite $favorite): static
    {
        if ($this->favorites->removeElement($favorite)) {
            // set the owning side to null (unless already changed)
            if ($favorite->getAutopart() === $this) {
                $favorite->setAutopart(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Cart>
     */
    public function getCarts(): Collection
    {
        return $this->carts;
    }

    public function addCart(Cart $cart): static
    {
        if (!$this->carts->contains($cart)) {
            $this->carts->add($cart);
            $cart->setAutopart($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): static
    {
        if ($this->carts->removeElement($cart)) {
            // set the owning side to null (unless already changed)
            if ($cart->getAutopart() === $this) {
                $cart->setAutopart(null);
            }
        }

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(string $imagePath): static
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }
}

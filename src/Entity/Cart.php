<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    private ?string $cartId = null;

    #[ORM\ManyToOne(inversedBy: 'carts')]
    #[ORM\JoinColumn(referencedColumnName: 'user_id', nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'carts')]
    #[ORM\JoinColumn(referencedColumnName: 'autopart_id', nullable: false)]
    private ?Autopart $autopart = null;

    public function __construct()
    {
        $this->cartId = uuid_create();
    }

    public function getCartId(): ?string
    {
        return $this->cartId;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getAutopart(): ?Autopart
    {
        return $this->autopart;
    }

    public function setAutopart(?Autopart $autopart): static
    {
        $this->autopart = $autopart;

        return $this;
    }

    public function __toString(): string
    {
        return sprintf('%s - %s', $this->getUser(), $this->getAutopart());
    }
}

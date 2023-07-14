<?php

namespace App\Entity;

use App\Repository\FavoriteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: FavoriteRepository::class)]
#[ORM\UniqueConstraint(fields: ['user', 'autopart'])]
#[UniqueEntity(fields: ['user', 'autopart'], message: 'Already in favorites',)]
class Favorite
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    #[Groups(['show'])]
    private ?string $favoriteId = null;

    #[ORM\ManyToOne(inversedBy: 'favorites')]
    #[ORM\JoinColumn(referencedColumnName: 'user_id', nullable: false)]
    #[Groups(['show'])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'favorites')]
    #[ORM\JoinColumn(referencedColumnName: 'autopart_id', nullable: false)]
    #[Groups(['show'])]
    private ?Autopart $autopart = null;

    public function __construct()
    {
        $this->favoriteId = uuid_create();
    }

    public function getFavoriteId(): ?string
    {
        return $this->favoriteId;
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

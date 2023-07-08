<?php

namespace App\Model;

use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class UserDTO
{
    public function __construct(
        #[Assert\Length(min: 5, max: 100)]
        public string $name,

        #[Assert\Email]
        public string $email,

        #[Assert\Length(min: 8, max: 50)]
        public string $password,
    ) {
    }
}
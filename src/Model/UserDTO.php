<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

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
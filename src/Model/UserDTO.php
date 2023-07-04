<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class UserDTO
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name,

        #[Assert\Email]
        public string $email,

        #[Assert\PasswordStrength]
        public string $password,
    )
    {
    }
}
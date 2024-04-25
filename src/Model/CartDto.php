<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class CartDto
{
    public function __construct(
        #[Assert\Length(min: 36, max: 36)]
        public string $userId,

        #[Assert\Length(min: 36, max: 36)]
        public string $autopartId,
    ) {
    }
}
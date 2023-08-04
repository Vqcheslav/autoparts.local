<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class ResponseDTO
{
    public function __construct(
        public bool $ok,

        #[Assert\Range(min: 100, max: 599)]
        public int $status,

        #[Assert\Length(min: 1, max: 255)]
        public string $detail,

        public mixed $data
    ) {
    }
}
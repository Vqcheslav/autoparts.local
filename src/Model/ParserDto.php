<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class ParserDto
{
    public function __construct(
        #[Assert\Url(), Assert\Length(min: 10, max: 250)]
        public string $url,

        #[Assert\LessThan(1000)]
        public int $page,

        #[Assert\Length(min: 36, max: 36)]
        public string $carId,
    ) {
    }
}

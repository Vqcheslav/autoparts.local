<?php

namespace App\Model\Autopart;

use Symfony\Component\Validator\Constraints as Assert;

class CreateAutopartDto
{
    public function __construct(
        #[Assert\Length(min: 36, max: 36)]
        public string $carId,

        #[Assert\Length(min: 36, max: 36)]
        public string $warehouseId,

        #[Assert\Length(min: 36, max: 36)]
        public string $manufacturerId,

        #[Assert\Length(min: 3, max: 250)]
        public string $title,

        #[Assert\Length(min: 3, max: 2000)]
        public string $description,

        #[Assert\Length(min: 3, max: 250)]
        public string $imagePath,

        #[Assert\Positive()]
        public int $price,

        #[Assert\Length(exactly: 3)]
        public string $currency,
    ) {
    }
}

<?php

namespace Domain\Order\DTOs;

use Support\Traits\Makeable;

class NewOrderDTO
{
    use Makeable;

    public function __construct(
        public readonly string $first_name,
        public readonly string $last_name,
        public readonly string $email,
        public readonly string $phone,
        public readonly string $city,
        public readonly string $address,
        public readonly string $password,
    )
    {
    }

}

<?php

namespace App\Models;

class Address
{
    public function __construct(
        public string $street,
        public string $city,
        public string $country,
        public string $postal_code
    ) {
    }
}

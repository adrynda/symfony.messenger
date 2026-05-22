<?php

namespace App\Core\Domain\DTO;

class RegistrationDTO
{
    public function __construct(
        public ?string $username = null,
        public ?string $email = null,
        public ?string $password = null,
    ) {
    }
}

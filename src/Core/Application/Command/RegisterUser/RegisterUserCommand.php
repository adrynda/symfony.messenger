<?php

namespace App\Core\Application\Command\RegisterUser;

use App\Core\Domain\DTO\RegistrationDTO;

readonly class RegisterUserCommand
{
    public function __construct(
        public RegistrationDTO $registrationDTO,
    ) {}
}

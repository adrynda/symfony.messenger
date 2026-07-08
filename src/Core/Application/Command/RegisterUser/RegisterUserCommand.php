<?php

namespace App\Core\Application\Command\RegisterUser;

use App\Core\Domain\DTO\RegistrationDTO;

final readonly class RegisterUserCommand
{
    public function __construct(
        public RegistrationDTO $registrationDTO,
    ) {}
}

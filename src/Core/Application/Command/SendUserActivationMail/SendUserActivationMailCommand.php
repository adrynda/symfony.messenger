<?php

namespace App\Core\Application\Command\SendUserActivationMail;

use App\Core\Domain\DTO\RegistrationDTO;

final readonly class SendUserActivationMailCommand
{
    public function __construct(
        public string $userIdentifier,
    ) {}
}

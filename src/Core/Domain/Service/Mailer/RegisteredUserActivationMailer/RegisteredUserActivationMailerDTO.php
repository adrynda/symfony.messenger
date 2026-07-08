<?php

namespace App\Core\Domain\Service\Mailer\RegisteredUserActivationMailer;

use App\Core\Domain\Model\User\User;

final readonly class RegisteredUserActivationMailerDTO
{
    public function __construct(
        public string $email,
        public string $username,
        public string $activationLink,
        public ?string $locale = null,
    ) {
    }
}

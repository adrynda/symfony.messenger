<?php

namespace App\Core\Domain\DTO;

use App\Core\UserInterface\Validator\UniqueEmail\UniqueEmail;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationDTO
{
    public function __construct(
        #[NotBlank]
        public ?string $username = null,
        #[NotBlank]
        #[Email]
        #[UniqueEmail]
        public ?string $email = null,
        #[NotBlank]
        // todo: PasswordStrength
        public ?string $password = null,
    ) {
    }
}

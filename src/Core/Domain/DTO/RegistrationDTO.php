<?php

namespace App\Core\Domain\DTO;

use Symfony\Component\Validator\Constraints as Assert;

readonly class RegistrationDTO
{
    public function __construct(
        #[Assert\NotBlank]
        public string $username,
        #[
            Assert\NotBlank,
            Assert\Email,
        ]
        public string $email,
        #[Assert\NotBlank]
        public string $password,
        #[Assert\NotBlank]
        public string $repeatPassword,

        public ?string $_csrfToken = null,
    ) {
    }

    #[Assert\IsTrue(message: 'Passwords do not match!')]
    public function isPasswordMatching(): bool
    {
        return $this->password === $this->repeatPassword;
    }
}

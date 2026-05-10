<?php

declare(strict_types=1);

namespace App\Messenger\_Shared\Domain\WriteModel\User;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Embeddable]
class Credentials implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct(
        #[ORM\Column(type: 'string', length: 50, unique: true)]
        public string $email,

        #[ORM\Column(type: 'string')]
        public string $password,
    ) {
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
        return;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}

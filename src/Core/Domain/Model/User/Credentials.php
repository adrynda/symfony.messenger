<?php

declare(strict_types=1);

namespace App\Core\Domain\Model\User;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Credentials
{
    public function __construct(
        #[ORM\Column(type: Types::STRING, length: 50, unique: true)]
        public string $email = '',

        #[ORM\Column(type: Types::STRING)]
        public ?string $password = null,

        #[ORM\Column(type: Types::BOOLEAN)]
        public bool $active = false,

        #[ORM\Column(type: Types::JSON)]
        public array $roles = ['ROLE_USER'],
    ) {
    }
}

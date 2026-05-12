<?php

declare(strict_types=1);

namespace App\Core\Domain\WriteModel\User;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Credentials
{
    public function __construct(
        #[ORM\Column(type: 'string', length: 50, unique: true)]
        public string $email = '',

        #[ORM\Column(type: 'string')]
        public ?string $password = null,
    ) {
    }
}

<?php

declare(strict_types=1);

namespace Olek\Audit\Tests\Integration\Fixture;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class AuditableTag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    public ?int $id = null;

    public function __construct(
        #[ORM\Column(type: Types::STRING, length: 255)]
        public string $name,
    ) {}
}

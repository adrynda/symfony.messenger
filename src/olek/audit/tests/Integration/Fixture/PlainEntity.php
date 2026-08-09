<?php

declare(strict_types=1);

namespace Olek\Audit\Tests\Integration\Fixture;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Encja świadomie bez #[Auditable] - do weryfikacji, że pakiet jej nie audytuje.
 */
#[ORM\Entity]
class PlainEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    public ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    public string $value = '';
}

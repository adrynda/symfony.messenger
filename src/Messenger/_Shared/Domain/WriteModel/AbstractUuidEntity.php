<?php

declare(strict_types=1);

namespace App\Messenger\_Shared\Domain\WriteModel;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\UuidV1;

#[ORM\MappedSuperclass]
abstract class AbstractUuidEntity
{
    protected function __construct(
        #[ORM\Id]
        #[ORM\Column(type: UuidType::NAME, unique: true)]
        #[ORM\GeneratedValue(strategy: 'NONE')]
        public readonly UuidV1 $id,
    ) {
    }
}

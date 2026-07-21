<?php

namespace Olek\Audit\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Entity
{
    public function __construct(
        #[ORM\Column(type: Types::STRING, length: 32)]
        public string $id,

        #[ORM\Column(type: Types::STRING, length: 255)]
        public string $class,
    ) {}
}

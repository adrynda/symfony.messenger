<?php

namespace Olek\Audit\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Olek\Audit\Enum\AuditActionTypeEnum;

#[ORM\Embeddable]
class Action
{
    public function __construct(
        #[ORM\Column(type: Types::ENUM, enumType: AuditActionTypeEnum::class)]
        public AuditActionTypeEnum $type,

        #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
        public DateTimeImmutable $timestamp,
    ) {}
}

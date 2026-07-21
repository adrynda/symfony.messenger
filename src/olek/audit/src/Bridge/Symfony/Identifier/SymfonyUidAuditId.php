<?php

namespace Olek\Audit\Bridge\Symfony\Identifier;

use Olek\Audit\Identifier\AuditIdInterface;
use Symfony\Component\Uid\UuidV1;

final readonly class SymfonyUidAuditId implements AuditIdInterface
{
    public function __construct(
        private UuidV1 $uuid,
    ) {}

    public function __toString(): string
    {
        return (string) $this->uuid;
    }
}

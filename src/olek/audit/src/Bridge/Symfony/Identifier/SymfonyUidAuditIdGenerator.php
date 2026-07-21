<?php

namespace Olek\Audit\Bridge\Symfony\Identifier;

use Olek\Audit\Identifier\AuditIdGeneratorInterface;
use Olek\Audit\Identifier\AuditIdInterface;
use Symfony\Component\Uid\Uuid;

final class SymfonyUidAuditIdGenerator implements AuditIdGeneratorInterface
{
    public function generate(): AuditIdInterface
    {
        return new SymfonyUidAuditId(Uuid::v1());
    }
}

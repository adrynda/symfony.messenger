<?php

namespace Olek\Audit\Identifier;

interface AuditIdGeneratorInterface
{
    public function generate(): AuditIdInterface;
}

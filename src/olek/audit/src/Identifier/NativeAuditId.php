<?php

namespace Olek\Audit\Identifier;

final readonly class NativeAuditId implements AuditIdInterface
{
    public function __construct(
        private string $value,
    ) {}

    public function __toString(): string
    {
        return $this->value;
    }
}

<?php

namespace Olek\Audit\DTO;

final readonly class AuditMetadata
{
    /**
     * @param string[] $ignoredProperties
     */
    public function __construct(
        public bool $isAudited,
        public array $ignoredProperties = [],
    ) {}
}

<?php

namespace Olek\Audit\DTO;

use Olek\Audit\Enum\AuditActionTypeEnum;

final readonly class AuditPayload
{
    /**
     * @param EntityPropertyDiff[] $diff
     */
    public function __construct(
        public string $entityClass,
        public string $entityId,
        public AuditActionTypeEnum $actionType,
        public array $diff,
        public \DateTimeImmutable $timestamp,
    ) {}
}

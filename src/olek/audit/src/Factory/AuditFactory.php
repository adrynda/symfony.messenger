<?php

namespace Olek\Audit\Factory;

use Olek\Audit\DTO\AuditPayload;
use Olek\Audit\Entity\Audit;
use Olek\Audit\Identifier\AuditIdGeneratorInterface;

final class AuditFactory
{
    public function __construct(
        private readonly AuditIdGeneratorInterface $idGenerator,
    ) {}

    public function createFromPayload(AuditPayload $payload): Audit
    {
        return Audit::create($this->idGenerator->generate(), $payload);
    }
}

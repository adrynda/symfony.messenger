<?php

declare(strict_types=1);

namespace Olek\Audit\Tests\Support\Fake;

use Doctrine\ORM\EntityManagerInterface;
use Olek\Audit\DTO\AuditPayload;
use Olek\Audit\Factory\AuditPayloadFactoryInterface;

final class InMemoryAuditPayloadFactory implements AuditPayloadFactoryInterface
{
    /** @var array<int, AuditPayload|null> */
    private array $forUpdate = [];

    /** @var array<int, AuditPayload|null> */
    private array $forDelete = [];

    public function willBuildForUpdate(object $entity, ?AuditPayload $payload): void
    {
        $this->forUpdate[spl_object_id($entity)] = $payload;
    }

    public function willBuildForDelete(object $entity, ?AuditPayload $payload): void
    {
        $this->forDelete[spl_object_id($entity)] = $payload;
    }

    public function buildForUpdate(EntityManagerInterface $em, object $entity): ?AuditPayload
    {
        return $this->forUpdate[spl_object_id($entity)] ?? null;
    }

    public function buildForDelete(EntityManagerInterface $em, object $entity): ?AuditPayload
    {
        return $this->forDelete[spl_object_id($entity)] ?? null;
    }
}

<?php

declare(strict_types=1);

namespace Olek\Audit\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Olek\Audit\DTO\AuditPayload;

interface AuditPayloadFactoryInterface
{
    public function buildForUpdate(EntityManagerInterface $em, object $entity): ?AuditPayload;

    public function buildForDelete(EntityManagerInterface $em, object $entity): ?AuditPayload;
}

<?php

namespace Olek\Audit\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Olek\Audit\DTO\AuditPayload;
use Olek\Audit\Factory\AuditFactory;

final readonly class AuditPayloadHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private AuditFactory $auditFactory,
    ) {
    }

    public function process(AuditPayload $payload): void
    {
        $entity = $this->auditFactory->createFromPayload($payload);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}

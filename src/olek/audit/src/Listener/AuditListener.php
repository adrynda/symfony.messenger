<?php

namespace Olek\Audit\Listener;

use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Olek\Audit\Dispatcher\AuditPayloadDispatcherInterface;
use Olek\Audit\DTO\AuditPayload;
use Olek\Audit\Factory\AuditPayloadFactory;

final class AuditListener
{
    /** @var AuditPayload[] */
    private array $pendingAudits = [];

    public function __construct(
        private readonly AuditPayloadFactory $payloadFactory,
        private readonly AuditPayloadDispatcherInterface $dispatcher,
    ) {}

    public function onFlush(OnFlushEventArgs $args): void
    {
        $em = $args->getObjectManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            $payload = $this->payloadFactory->buildForUpdate($em, $entity);

            if ($payload !== null) {
                $this->pendingAudits[] = $payload;
            }
        }

        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            $payload = $this->payloadFactory->buildForDelete($em, $entity);

            if ($payload !== null) {
                $this->pendingAudits[] = $payload;
            }
        }
    }

    public function postFlush(PostFlushEventArgs $args): void
    {
        foreach ($this->pendingAudits as $payload) {
            $this->dispatcher->dispatch($payload);
        }

        $this->pendingAudits = [];
    }
}

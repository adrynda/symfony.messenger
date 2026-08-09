<?php

declare(strict_types=1);

namespace Olek\Audit\Tests\Unit\Listener;

use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\UnitOfWork;
use Olek\Audit\DTO\AuditPayload;
use Olek\Audit\Enum\AuditActionTypeEnum;
use Olek\Audit\Listener\AuditListener;
use Olek\Audit\Tests\Support\Fake\InMemoryAuditPayloadDispatcher;
use Olek\Audit\Tests\Support\Fake\InMemoryAuditPayloadFactory;

final class AuditListenerTest extends Unit
{
    public function testOnFlushCollectsPayloadsAndPostFlushDispatchesThemInOrder(): void
    {
        $updatedWithPayload = new \stdClass();
        $updatedWithoutPayload = new \stdClass();
        $deletedWithPayload = new \stdClass();

        $updatePayload = $this->payload('update');
        $deletePayload = $this->payload('delete');

        $factory = new InMemoryAuditPayloadFactory();
        $factory->willBuildForUpdate($updatedWithPayload, $updatePayload);
        $factory->willBuildForUpdate($updatedWithoutPayload, null);
        $factory->willBuildForDelete($deletedWithPayload, $deletePayload);

        $dispatcher = new InMemoryAuditPayloadDispatcher();
        $listener = new AuditListener($factory, $dispatcher);

        $em = $this->entityManagerScheduling(
            updates: [$updatedWithPayload, $updatedWithoutPayload],
            deletions: [$deletedWithPayload],
        );

        $listener->onFlush(new OnFlushEventArgs($em));
        $this->assertSame([], $dispatcher->dispatched, 'onFlush nie powinien jeszcze nic dispatchować');

        $listener->postFlush(new PostFlushEventArgs($em));

        $this->assertSame([$updatePayload, $deletePayload], $dispatcher->dispatched);
    }

    public function testPendingBufferIsClearedAfterPostFlush(): void
    {
        $entity = new \stdClass();
        $payload = $this->payload('update');

        $factory = new InMemoryAuditPayloadFactory();
        $factory->willBuildForUpdate($entity, $payload);

        $dispatcher = new InMemoryAuditPayloadDispatcher();
        $listener = new AuditListener($factory, $dispatcher);

        $em = $this->entityManagerScheduling(updates: [$entity], deletions: []);

        $listener->onFlush(new OnFlushEventArgs($em));
        $listener->postFlush(new PostFlushEventArgs($em));

        $this->assertCount(1, $dispatcher->dispatched);

        // kolejny postFlush bez poprzedzającego onFlush (np. flush bez żadnych zmian)
        // nie powinien ponownie dispatchować tego samego payloadu
        $listener->postFlush(new PostFlushEventArgs($em));

        $this->assertCount(1, $dispatcher->dispatched);
    }

    private function payload(string $suffix): AuditPayload
    {
        return new AuditPayload(
            entityClass: 'App\\Entity\\Fixture',
            entityId: $suffix,
            actionType: AuditActionTypeEnum::Update,
            diff: [],
            timestamp: new \DateTimeImmutable('2026-01-01T00:00:00+00:00'),
        );
    }

    /**
     * @param object[] $updates
     * @param object[] $deletions
     */
    private function entityManagerScheduling(array $updates, array $deletions): EntityManagerInterface
    {
        $uow = $this->createMock(UnitOfWork::class);
        $uow->method('getScheduledEntityUpdates')->willReturn($updates);
        $uow->method('getScheduledEntityDeletions')->willReturn($deletions);

        $em = $this->createMock(EntityManagerInterface::class);
        $em->method('getUnitOfWork')->willReturn($uow);

        return $em;
    }
}

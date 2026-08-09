<?php

declare(strict_types=1);

namespace Olek\Audit\Tests\Unit\Bridge\Symfony\Dispatcher;

use Codeception\Test\Unit;
use Olek\Audit\Bridge\Symfony\Dispatcher\MessengerAuditPayloadDispatcher;
use Olek\Audit\DTO\AuditPayload;
use Olek\Audit\Enum\AuditActionTypeEnum;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessengerAuditPayloadDispatcherTest extends Unit
{
    public function testDispatchDelegatesPayloadToMessageBus(): void
    {
        $payload = new AuditPayload(
            entityClass: 'App\\Entity\\User',
            entityId: '42',
            actionType: AuditActionTypeEnum::Update,
            diff: [],
            timestamp: new \DateTimeImmutable('2026-01-01T00:00:00+00:00'),
        );

        $bus = $this->createMock(MessageBusInterface::class);
        $bus->expects($this->once())
            ->method('dispatch')
            ->with($payload)
            ->willReturn(new Envelope($payload));

        (new MessengerAuditPayloadDispatcher($bus))->dispatch($payload);
    }
}

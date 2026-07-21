<?php

namespace Olek\Audit\Bridge\Symfony\Dispatcher;

use Olek\Audit\Dispatcher\AuditPayloadDispatcherInterface;
use Olek\Audit\DTO\AuditPayload;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessengerAuditPayloadDispatcher implements AuditPayloadDispatcherInterface
{
    public function __construct(
        private readonly MessageBusInterface $bus,
    ) {}

    public function dispatch(AuditPayload $payload): void
    {
        $this->bus->dispatch($payload);
    }
}

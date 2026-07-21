<?php

namespace Olek\Audit\Dispatcher;

use Olek\Audit\DTO\AuditPayload;
use Olek\Audit\Handler\AuditPayloadHandler;

final class InlineAuditPayloadDispatcher implements AuditPayloadDispatcherInterface
{
    public function __construct(
        private readonly AuditPayloadHandler $handler,
    ) {}

    public function dispatch(AuditPayload $payload): void
    {
        $this->handler->process($payload);
    }
}

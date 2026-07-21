<?php

namespace Olek\Audit\Dispatcher;

use Olek\Audit\DTO\AuditPayload;

interface AuditPayloadDispatcherInterface
{
    public function dispatch(AuditPayload $payload): void;
}

<?php

declare(strict_types=1);

namespace Olek\Audit\Tests\Support\Fake;

use Olek\Audit\Dispatcher\AuditPayloadDispatcherInterface;
use Olek\Audit\DTO\AuditPayload;

final class InMemoryAuditPayloadDispatcher implements AuditPayloadDispatcherInterface
{
    /** @var AuditPayload[] */
    public array $dispatched = [];

    public function dispatch(AuditPayload $payload): void
    {
        $this->dispatched[] = $payload;
    }
}

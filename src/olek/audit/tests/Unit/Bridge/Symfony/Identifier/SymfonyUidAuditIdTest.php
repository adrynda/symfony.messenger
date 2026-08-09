<?php

declare(strict_types=1);

namespace Olek\Audit\Tests\Unit\Bridge\Symfony\Identifier;

use Codeception\Test\Unit;
use Olek\Audit\Bridge\Symfony\Identifier\SymfonyUidAuditId;
use Symfony\Component\Uid\UuidV1;

final class SymfonyUidAuditIdTest extends Unit
{
    public function testToStringReturnsUnderlyingUuidRepresentation(): void
    {
        $uuid = new UuidV1('00000000-0000-1000-8000-000000000000');

        $id = new SymfonyUidAuditId($uuid);

        $this->assertSame((string) $uuid, (string) $id);
    }
}

<?php

declare(strict_types=1);

namespace Olek\Audit\Tests\Unit\Identifier;

use Codeception\Test\Unit;
use Olek\Audit\Identifier\NativeAuditId;

final class NativeAuditIdTest extends Unit
{
    public function testToStringReturnsWrappedValue(): void
    {
        $id = new NativeAuditId('11111111-1111-4111-8111-111111111111');

        $this->assertSame('11111111-1111-4111-8111-111111111111', (string) $id);
        $this->assertSame('11111111-1111-4111-8111-111111111111', $id->__toString());
    }
}

<?php

declare(strict_types=1);

namespace Olek\Audit\Tests\Unit\Identifier;

use Codeception\Test\Unit;
use Olek\Audit\Identifier\NativeAuditIdGenerator;

final class NativeAuditIdGeneratorTest extends Unit
{
    private const UUID_V4_PATTERN = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/';

    public function testGeneratesValidUuidV4(): void
    {
        $id = (new NativeAuditIdGenerator())->generate();

        $this->assertMatchesRegularExpression(self::UUID_V4_PATTERN, (string) $id);
    }

    public function testGeneratesDifferentValuesOnEachCall(): void
    {
        $generator = new NativeAuditIdGenerator();

        $first = (string) $generator->generate();
        $second = (string) $generator->generate();

        $this->assertNotSame($first, $second);
    }
}

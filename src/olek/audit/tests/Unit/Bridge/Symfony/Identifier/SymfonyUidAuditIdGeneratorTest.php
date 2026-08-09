<?php

declare(strict_types=1);

namespace Olek\Audit\Tests\Unit\Bridge\Symfony\Identifier;

use Codeception\Test\Unit;
use Olek\Audit\Bridge\Symfony\Identifier\SymfonyUidAuditId;
use Olek\Audit\Bridge\Symfony\Identifier\SymfonyUidAuditIdGenerator;
use Symfony\Component\Uid\Uuid;

final class SymfonyUidAuditIdGeneratorTest extends Unit
{
    public function testGeneratesSymfonyUidAuditIdWithValidUuid(): void
    {
        $id = (new SymfonyUidAuditIdGenerator())->generate();

        $this->assertInstanceOf(SymfonyUidAuditId::class, $id);
        $this->assertTrue(Uuid::isValid((string) $id));
    }

    public function testGeneratesDifferentValuesOnEachCall(): void
    {
        $generator = new SymfonyUidAuditIdGenerator();

        $first = (string) $generator->generate();
        $second = (string) $generator->generate();

        $this->assertNotSame($first, $second);
    }
}

<?php

declare(strict_types=1);

namespace Olek\Audit\Tests\Integration;

use Codeception\Test\Unit;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Olek\Audit\Doctrine\AuditIdType;
use Olek\Audit\Identifier\NativeAuditId;

final class AuditIdTypeTest extends Unit
{
    public function testConvertToDatabaseValueStringifiesTheId(): void
    {
        $type = new AuditIdType();

        $result = $type->convertToDatabaseValue(
            new NativeAuditId('11111111-1111-4111-8111-111111111111'),
            $this->createMock(AbstractPlatform::class),
        );

        $this->assertSame('11111111-1111-4111-8111-111111111111', $result);
    }

    public function testConvertToDatabaseValuePassesNullThrough(): void
    {
        $type = new AuditIdType();

        $this->assertNull($type->convertToDatabaseValue(null, $this->createMock(AbstractPlatform::class)));
    }

    public function testConvertToPhpValueWrapsInNativeAuditId(): void
    {
        $type = new AuditIdType();

        $result = $type->convertToPHPValue(
            '11111111-1111-4111-8111-111111111111',
            $this->createMock(AbstractPlatform::class),
        );

        $this->assertInstanceOf(NativeAuditId::class, $result);
        $this->assertSame('11111111-1111-4111-8111-111111111111', (string) $result);
    }

    public function testConvertToPhpValuePassesNullThrough(): void
    {
        $type = new AuditIdType();

        $this->assertNull($type->convertToPHPValue(null, $this->createMock(AbstractPlatform::class)));
    }

    public function testGetSqlDeclarationDelegatesToPlatformGuidType(): void
    {
        $type = new AuditIdType();
        $column = ['name' => 'id'];

        $platform = $this->createMock(AbstractPlatform::class);
        $platform->expects($this->once())
            ->method('getGuidTypeDeclarationSQL')
            ->with($column)
            ->willReturn('CHAR(36)');

        $this->assertSame('CHAR(36)', $type->getSQLDeclaration($column, $platform));
    }
}

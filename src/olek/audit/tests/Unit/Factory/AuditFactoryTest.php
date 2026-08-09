<?php

declare(strict_types=1);

namespace Olek\Audit\Tests\Unit\Factory;

use Codeception\Test\Unit;
use Olek\Audit\DTO\AuditPayload;
use Olek\Audit\DTO\EntityPropertyDiff;
use Olek\Audit\Enum\AuditActionTypeEnum;
use Olek\Audit\Factory\AuditFactory;
use Olek\Audit\Identifier\AuditIdGeneratorInterface;
use Olek\Audit\Identifier\NativeAuditId;

final class AuditFactoryTest extends Unit
{
    public function testCreatesAuditFromPayloadUsingGeneratedId(): void
    {
        $generatedId = new NativeAuditId('11111111-1111-4111-8111-111111111111');

        $idGenerator = $this->createMock(AuditIdGeneratorInterface::class);
        $idGenerator->expects($this->once())
            ->method('generate')
            ->willReturn($generatedId);

        $timestamp = new \DateTimeImmutable('2026-01-01T00:00:00+00:00');
        $diff = [new EntityPropertyDiff('name', 'old', 'new')];

        $payload = new AuditPayload(
            entityClass: 'App\\Entity\\User',
            entityId: '42',
            actionType: AuditActionTypeEnum::Update,
            diff: $diff,
            timestamp: $timestamp,
            actor: 'jkowalski',
        );

        $audit = (new AuditFactory($idGenerator))->createFromPayload($payload);

        $this->assertSame($generatedId, $audit->id);
        $this->assertSame('App\\Entity\\User', $audit->entity->class);
        $this->assertSame('42', $audit->entity->id);
        $this->assertSame(AuditActionTypeEnum::Update, $audit->action->type);
        $this->assertSame($timestamp, $audit->action->timestamp);
        $this->assertSame('jkowalski', $audit->actor);
        $this->assertSame([], $audit->diff);
    }

    public function testActorDefaultsToNullWhenPayloadHasNone(): void
    {
        $idGenerator = $this->createMock(AuditIdGeneratorInterface::class);
        $idGenerator->method('generate')->willReturn(new NativeAuditId('11111111-1111-4111-8111-111111111111'));

        $payload = new AuditPayload(
            entityClass: 'App\\Entity\\User',
            entityId: '42',
            actionType: AuditActionTypeEnum::Delete,
            diff: [],
            timestamp: new \DateTimeImmutable('2026-01-01T00:00:00+00:00'),
        );

        $audit = (new AuditFactory($idGenerator))->createFromPayload($payload);

        $this->assertNull($audit->actor);
    }
}

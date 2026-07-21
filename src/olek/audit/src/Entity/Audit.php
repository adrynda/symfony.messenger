<?php

namespace Olek\Audit\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Olek\Audit\Doctrine\AuditIdType;
use Olek\Audit\DTO\AuditPayload;
use Olek\Audit\Identifier\AuditIdInterface;

#[ORM\Entity]
#[ORM\Table(name: 'audits')]
class Audit
{
    private function __construct(
        #[ORM\Id]
        #[ORM\Column(type: AuditIdType::NAME)]
        public readonly AuditIdInterface $id,

        #[ORM\Column(type: Types::TEXT)]
        public string $username = '',

        #[ORM\Embedded(Entity::class)]
        public Entity $entity,

        #[ORM\Embedded(Action::class)]
        public Action $action,

        #[ORM\Column(type: Types::JSON)]
        public array $diff = [],
    ) {}

    public static function create(AuditIdInterface $id, AuditPayload $payload): static
    {
        return new static(
            id: $id,
            entity: new Entity(
                id: $payload->entityId,
                class: $payload->entityClass,
            ),
            action: new Action(
                type: $payload->actionType,
                timestamp: $payload->timestamp,
            ),
        );
    }
}

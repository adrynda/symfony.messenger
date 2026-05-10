<?php

declare(strict_types=1);

namespace App\Messenger\_Shared\DataFixture;

use App\Messenger\_Shared\Domain\WriteModel\AbstractUuidEntity;
use ReflectionClass;
use Symfony\Component\Uid\UuidV1;

abstract class AbstractFixture
{
    protected static function createEntityWithDefaultId(
        string $entityClass,
        UuidV1 $id,
    ): AbstractUuidEntity {
        $reflection = new ReflectionClass($entityClass);
        /** @var AbstractUuidEntity $entity */
        $entity = $reflection->newInstanceWithoutConstructor();

        $abstractReflection = new ReflectionClass(AbstractUuidEntity::class);

        $property = $abstractReflection->getProperty('id');
        $property->setValue($entity, $id);

        return $entity;
    }

    protected static function setReflectedPropertyValue(
        object $entity,
        string $property,
        mixed $value,
    ): void {
        $reflection = new ReflectionClass($entity::class);
        $property = $reflection->getProperty($property);
        $property->setValue($entity, $value);
    }
}

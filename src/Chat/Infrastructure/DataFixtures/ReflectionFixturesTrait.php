<?php

declare(strict_types=1);

namespace App\Chat\Infrastructure\DataFixtures;

use App\Core\Domain\Model\AbstractUuidEntity;
use ReflectionClass;
use Symfony\Component\Uid\UuidV1;

trait ReflectionFixturesTrait
{
    protected function createEntityWithDefaultId(
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

    protected function setReflectedPropertyValue(
        object $entity,
        string $property,
        mixed $value,
    ): void {
        $reflection = new ReflectionClass($entity::class);
        $property = $reflection->getProperty($property);
        $property->setValue($entity, $value);
    }
}

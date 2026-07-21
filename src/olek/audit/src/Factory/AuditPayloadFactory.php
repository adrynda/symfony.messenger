<?php

namespace Olek\Audit\Factory;

use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Olek\Audit\Doctrine\EntityIdentifierResolver;
use Olek\Audit\DTO\AuditMetadata;
use Olek\Audit\DTO\AuditPayload;
use Olek\Audit\DTO\EntityPropertyDiff;
use Olek\Audit\Enum\AuditActionTypeEnum;

final class AuditPayloadFactory
{
    public function __construct(
        private readonly AuditMetadataFactory $metadataFactory,
    ) {}

    public function buildForUpdate(EntityManagerInterface $em, object $entity): ?AuditPayload
    {
        $metadata = $this->metadataFactory->getMetadataFor($entity::class);

        if ($metadata === null) {
            return null;
        }

        $classMetadata = $em->getClassMetadata($entity::class);
        $changeSet = $em->getUnitOfWork()->getEntityChangeSet($entity);
        $diff = [];

        foreach ($changeSet as $field => [$oldValue, $newValue]) {
            if (\in_array($field, $metadata->ignoredProperties, true)) {
                continue;
            }

            if ($classMetadata->isSingleValuedAssociation($field)) {
                $diff[] = new EntityPropertyDiff(
                    $field,
                    $oldValue === null ? null : EntityIdentifierResolver::resolve($em, $oldValue),
                    $newValue === null ? null : EntityIdentifierResolver::resolve($em, $newValue),
                );

                continue;
            }

            $diff[] = new EntityPropertyDiff($field, $oldValue, $newValue);
        }

        foreach ($this->buildCollectionDiffs($em, $entity, $metadata) as $collectionDiff) {
            $diff[] = $collectionDiff;
        }

        if ($diff === []) {
            return null;
        }

        return new AuditPayload(
            entityClass: $entity::class,
            entityId: EntityIdentifierResolver::resolve($em, $entity),
            actionType: AuditActionTypeEnum::Update,
            diff: $diff,
            timestamp: new DateTimeImmutable(),
        );
    }

    public function buildForDelete(EntityManagerInterface $em, object $entity): ?AuditPayload
    {
        $metadata = $this->metadataFactory->getMetadataFor($entity::class);

        if ($metadata === null) {
            return null;
        }

        $classMetadata = $em->getClassMetadata($entity::class);
        $diff = [];

        foreach ($classMetadata->getFieldNames() as $field) {
            if (\in_array($field, $metadata->ignoredProperties, true)) {
                continue;
            }

            $diff[] = new EntityPropertyDiff($field, $classMetadata->getFieldValue($entity, $field), null);
        }

        foreach ($classMetadata->getAssociationNames() as $field) {
            if (\in_array($field, $metadata->ignoredProperties, true)) {
                continue;
            }

            $value = $classMetadata->getFieldValue($entity, $field);

            if ($classMetadata->isSingleValuedAssociation($field)) {
                $diff[] = new EntityPropertyDiff(
                    $field,
                    $value === null ? null : EntityIdentifierResolver::resolve($em, $value),
                    null,
                );

                continue;
            }

            $diff[] = new EntityPropertyDiff($field, $this->resolveCollectionIds($em, $value), []);
        }

        return new AuditPayload(
            entityClass: $entity::class,
            entityId: EntityIdentifierResolver::resolve($em, $entity),
            actionType: AuditActionTypeEnum::Delete,
            diff: $diff,
            timestamp: new DateTimeImmutable(),
        );
    }

    /**
     * @return EntityPropertyDiff[]
     */
    private function buildCollectionDiffs(EntityManagerInterface $em, object $entity, AuditMetadata $metadata): array
    {
        $diffs = [];

        foreach ($em->getUnitOfWork()->getScheduledCollectionUpdates() as $collection) {
            if ($collection->getOwner() !== $entity) {
                continue;
            }

            $fieldName = $collection->getMapping()->fieldName;

            if (\in_array($fieldName, $metadata->ignoredProperties, true)) {
                continue;
            }

            $diffs[] = new EntityPropertyDiff(
                $fieldName,
                $this->resolveCollectionIds($em, $collection->getSnapshot()),
                $this->resolveCollectionIds($em, $collection->toArray()),
            );
        }

        return $diffs;
    }

    /**
     * @param PersistentCollection<array-key, object>|iterable<object>|null $collection
     * @return array<int, int|string>
     */
    private function resolveCollectionIds(EntityManagerInterface $em, PersistentCollection|iterable|null $collection): array
    {
        if ($collection === null) {
            return [];
        }

        $ids = [];
        foreach ($collection as $related) {
            $ids[] = EntityIdentifierResolver::resolve($em, $related);
        }

        return $ids;
    }
}

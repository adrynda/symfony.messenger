<?php

namespace Olek\Audit\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

final class EntityIdentifierResolver
{
    public static function resolve(EntityManagerInterface $em, object $entity): string
    {
        $identifier = $em->getClassMetadata($entity::class)->getIdentifierValues($entity);

        return implode(',', array_map(
            static fn (mixed $value): string => (string) $value,
            $identifier,
        ));
    }
}

<?php

namespace Olek\Audit\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Olek\Audit\Identifier\AuditIdInterface;
use Olek\Audit\Identifier\NativeAuditId;

final class AuditIdType extends Type
{
    public const NAME = 'audit_id';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getGuidTypeDeclarationSQL($column);
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        return $value === null ? null : (string) $value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?AuditIdInterface
    {
        // todo: czy nie dałoby sie tutaj wstrzyknąć w constructorze jaki jest używany typ?
        // Przy odczycie z bazy nie wiemy, który generator stworzył ID - AuditIdInterface
        // wymaga tylko __toString(), więc opakowujemy w neutralną, natywną implementację.
        return $value === null ? null : new NativeAuditId($value);
    }
}

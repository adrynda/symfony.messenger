<?php

declare(strict_types=1);

namespace Olek\Audit\Tests\Integration\Support;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use Olek\Audit\Doctrine\AuditIdType;

final class EntityManagerFactory
{
    public static function createInMemory(): EntityManagerInterface
    {
        if (!Type::hasType(AuditIdType::NAME)) {
            Type::addType(AuditIdType::NAME, AuditIdType::class);
        }

        $config = ORMSetup::createAttributeMetadataConfiguration(
            paths: [
                \dirname(__DIR__, 3) . '/src/Entity',
                \dirname(__DIR__) . '/Fixture',
            ],
            isDevMode: true,
        );

        $connection = DriverManager::getConnection(
            ['driver' => 'pdo_sqlite', 'memory' => true],
            $config,
        );

        $em = new EntityManager($connection, $config);

        $schemaTool = new SchemaTool($em);
        $schemaTool->createSchema($em->getMetadataFactory()->getAllMetadata());

        return $em;
    }
}

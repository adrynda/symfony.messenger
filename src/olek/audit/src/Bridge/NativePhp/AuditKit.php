<?php

namespace Olek\Audit\Bridge\NativePhp;

use Doctrine\ORM\EntityManagerInterface;
use Olek\Audit\Cache\FilesystemMetadataCache;
use Olek\Audit\Dispatcher\InlineAuditPayloadDispatcher;
use Olek\Audit\Factory\AuditFactory;
use Olek\Audit\Factory\AuditMetadataFactory;
use Olek\Audit\Factory\AuditPayloadFactory;
use Olek\Audit\Handler\AuditPayloadHandler;
use Olek\Audit\Identifier\NativeAuditIdGenerator;
use Olek\Audit\Listener\AuditListener;

/**
 * Ręczne złożenie pakietu poza kontenerem DI/bundlem - do użycia wszędzie tam,
 * gdzie nie ma frameworka (albo nie ma dla niego osobnego Bridge). Używa
 * wyłącznie uniwersalnych implementacji z rdzenia.
 */
final class AuditKit
{
    public static function register(EntityManagerInterface $entityManager, string $cacheDir): void
    {
        $metadataFactory = new AuditMetadataFactory(new FilesystemMetadataCache($cacheDir));
        $payloadFactory = new AuditPayloadFactory($metadataFactory);
        $auditFactory = new AuditFactory(new NativeAuditIdGenerator());
        $handler = new AuditPayloadHandler($entityManager, $auditFactory);
        $dispatcher = new InlineAuditPayloadDispatcher($handler);
        $listener = new AuditListener($payloadFactory, $dispatcher);

        $entityManager->getEventManager()->addEventListener(['onFlush', 'postFlush'], $listener);
    }
}

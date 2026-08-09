<?php

declare(strict_types=1);

namespace Olek\Audit\Tests\Integration\Support;

use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;
use Olek\Audit\Actor\NullActorResolver;
use Olek\Audit\Cache\FilesystemMetadataCache;
use Olek\Audit\Factory\AuditMetadataFactory;
use Olek\Audit\Factory\AuditPayloadFactory;
use Olek\Audit\Listener\AuditListener;
use Olek\Audit\Tests\Support\Fake\InMemoryAuditPayloadDispatcher;

/**
 * Wpina realny AuditListener + AuditPayloadFactory na realnym Doctrine ORM (SQLite :memory:)
 * i przechwytuje wysyłane payloady przez in-memory dispatcher - dokładnie ten sam skład,
 * którego używa Bridge\NativePhp\AuditKit, tylko z podmienionym dispatcherem.
 */
abstract class AuditIntegrationTestCase extends Unit
{
    protected EntityManagerInterface $em;

    protected InMemoryAuditPayloadDispatcher $dispatcher;

    private string $cacheDir;

    protected function _before(): void
    {
        $this->em = EntityManagerFactory::createInMemory();
        $this->cacheDir = sys_get_temp_dir() . '/olek-audit-integration-' . uniqid('', true);

        $metadataFactory = new AuditMetadataFactory(new FilesystemMetadataCache($this->cacheDir));
        $payloadFactory = new AuditPayloadFactory($metadataFactory, new NullActorResolver());
        $this->dispatcher = new InMemoryAuditPayloadDispatcher();

        $listener = new AuditListener($payloadFactory, $this->dispatcher);
        $this->em->getEventManager()->addEventListener(['onFlush', 'postFlush'], $listener);
    }

    protected function _after(): void
    {
        if (!is_dir($this->cacheDir)) {
            return;
        }

        foreach (glob($this->cacheDir . '/*') ?: [] as $file) {
            unlink($file);
        }

        rmdir($this->cacheDir);
    }
}

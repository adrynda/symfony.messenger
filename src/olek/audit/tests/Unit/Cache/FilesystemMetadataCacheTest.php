<?php

declare(strict_types=1);

namespace Olek\Audit\Tests\Unit\Cache;

use Codeception\Test\Unit;
use Olek\Audit\Cache\FilesystemMetadataCache;

final class FilesystemMetadataCacheTest extends Unit
{
    private string $directory;

    private FilesystemMetadataCache $cache;

    protected function _before(): void
    {
        $this->directory = sys_get_temp_dir() . '/olek-audit-cache-' . uniqid('', true);
        $this->cache = new FilesystemMetadataCache($this->directory);
    }

    protected function _after(): void
    {
        if (!is_dir($this->directory)) {
            return;
        }

        foreach (glob($this->directory . '/*') ?: [] as $file) {
            unlink($file);
        }

        rmdir($this->directory);
    }

    public function testGetReturnsDefaultWhenKeyMissing(): void
    {
        $this->assertNull($this->cache->get('missing'));
        $this->assertSame('default', $this->cache->get('missing', 'default'));
    }

    public function testSetThenGetRoundTripsValue(): void
    {
        $this->cache->set('key', ['isAudited' => true]);

        $this->assertSame(['isAudited' => true], $this->cache->get('key'));
    }

    public function testHasReflectsPresence(): void
    {
        $this->assertFalse($this->cache->has('key'));

        $this->cache->set('key', 'value');

        $this->assertTrue($this->cache->has('key'));
    }

    public function testDeleteRemovesValue(): void
    {
        $this->cache->set('key', 'value');
        $this->cache->delete('key');

        $this->assertFalse($this->cache->has('key'));
        $this->assertNull($this->cache->get('key'));
    }

    public function testDeleteOfMissingKeyIsNoop(): void
    {
        $this->assertTrue($this->cache->delete('missing'));
    }

    public function testClearRemovesAllValues(): void
    {
        $this->cache->set('a', 1);
        $this->cache->set('b', 2);

        $this->cache->clear();

        $this->assertFalse($this->cache->has('a'));
        $this->assertFalse($this->cache->has('b'));
    }

    public function testSetMultipleThenGetMultiple(): void
    {
        $this->cache->setMultiple(['a' => 1, 'b' => 2]);

        $this->assertSame(
            ['a' => 1, 'b' => 2, 'c' => 'default'],
            $this->cache->getMultiple(['a', 'b', 'c'], 'default'),
        );
    }

    public function testDeleteMultipleRemovesGivenKeys(): void
    {
        $this->cache->setMultiple(['a' => 1, 'b' => 2]);

        $this->cache->deleteMultiple(['a', 'b']);

        $this->assertFalse($this->cache->has('a'));
        $this->assertFalse($this->cache->has('b'));
    }

    public function testKeyIsSanitizedToSafeFilename(): void
    {
        $this->cache->set('App\\Entity\\User::class', 'value');

        $this->assertSame('value', $this->cache->get('App\\Entity\\User::class'));
    }
}

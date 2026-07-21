<?php

namespace Olek\Audit\Cache;

use Psr\SimpleCache\CacheInterface;

final class FilesystemMetadataCache implements CacheInterface
{
    public function __construct(
        private readonly string $directory,
    ) {}

    public function get(string $key, mixed $default = null): mixed
    {
        $path = $this->pathFor($key);

        if (!is_file($path)) {
            return $default;
        }

        return require $path;
    }

    public function set(string $key, mixed $value, \DateInterval|int|null $ttl = null): bool
    {
        $path = $this->pathFor($key);

        if (!is_dir(\dirname($path))) {
            mkdir(\dirname($path), 0775, true);
        }

        file_put_contents($path, '<?php return ' . var_export($value, true) . ';');

        return true;
    }

    public function delete(string $key): bool
    {
        $path = $this->pathFor($key);

        if (is_file($path)) {
            unlink($path);
        }

        return true;
    }

    public function clear(): bool
    {
        foreach (glob($this->directory . '/*.php') ?: [] as $file) {
            unlink($file);
        }

        return true;
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        $result = [];

        foreach ($keys as $key) {
            $result[$key] = $this->get($key, $default);
        }

        return $result;
    }

    public function setMultiple(iterable $values, \DateInterval|int|null $ttl = null): bool
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value, $ttl);
        }

        return true;
    }

    public function deleteMultiple(iterable $keys): bool
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }

        return true;
    }

    public function has(string $key): bool
    {
        return is_file($this->pathFor($key));
    }

    private function pathFor(string $key): string
    {
        return $this->directory . '/' . preg_replace('/[^A-Za-z0-9_.]/', '.', $key) . '.php';
    }
}

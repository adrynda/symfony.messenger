<?php

namespace Olek\Audit\Factory;

use Olek\Audit\Attribute\Auditable;
use Olek\Audit\Attribute\Ignore;
use Olek\Audit\DTO\AuditMetadata;
use Psr\SimpleCache\CacheInterface;
use ReflectionClass;

final class AuditMetadataFactory
{
    /** @var array<string, AuditMetadata|null> */
    private array $localCache = [];

    public function __construct(
        private readonly CacheInterface $cache,
    ) {}

    public function getMetadataFor(string $class): ?AuditMetadata
    {
        if (\array_key_exists($class, $this->localCache)) {
            return $this->localCache[$class];
        }

        $key = str_replace('\\', '.', $class);

        if (!$this->cache->has($key)) {
            $this->cache->set($key, $this->buildMetadata($class));
        }

        $data = $this->cache->get($key);

        return $this->localCache[$class] = $data === null
            ? null
            : new AuditMetadata($data['isAudited'], $data['ignoredProperties']);
    }

    /**
     * @return array{isAudited: bool, ignoredProperties: string[]}|null
     */
    private function buildMetadata(string $class): ?array
    {
        $reflection = new ReflectionClass($class);

        if ($reflection->getAttributes(Auditable::class) === []) {
            return null;
        }

        $ignored = [];
        foreach ($reflection->getProperties() as $property) {
            if ($property->getAttributes(Ignore::class) !== []) {
                $ignored[] = $property->getName();
            }
        }

        return ['isAudited' => true, 'ignoredProperties' => $ignored];
    }
}

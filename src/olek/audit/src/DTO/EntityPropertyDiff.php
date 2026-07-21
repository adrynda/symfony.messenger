<?php

namespace Olek\Audit\DTO;

final readonly class EntityPropertyDiff
{
    public function __construct(
        public string $name,
        public mixed $oldValue,
        public mixed $newValue,
    ) {}

    /**
     * @return array{name: string, oldValue: mixed, newValue: mixed}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'oldValue' => $this->oldValue,
            'newValue' => $this->newValue,
        ];
    }
}

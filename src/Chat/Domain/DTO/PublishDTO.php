<?php

declare(strict_types=1);

namespace App\Chat\Domain\DTO;

abstract readonly class PublishDTO
{
    public function __construct(
        public string $topic,
        public array $data,
    ) {
    }
}

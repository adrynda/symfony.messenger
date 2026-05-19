<?php

namespace App\Chat\Mercure\Application\Service;

use Symfony\Component\Uid\UuidV1;

readonly class CreateChatDTO
{
    public function __construct(
        /** @var UuidV1[] $users */
        public array $users,
    ) {}
}

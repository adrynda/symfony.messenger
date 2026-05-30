<?php

declare(strict_types=1);

namespace App\Chat\Application\Query\FindUserChats;

use Symfony\Component\Uid\UuidV1;

final readonly class FindUserChatsQuery
{
    public function __construct(
        public UuidV1 $userId,
    ) {
    }
}

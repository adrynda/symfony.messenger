<?php

declare(strict_types=1);

namespace App\Chat\Mercure\Application\Query\LoadChat;

use Symfony\Component\Uid\UuidV1;

final readonly class LoadChatQuery
{
    public function __construct(
        public UuidV1 $chatId,
        public int $limit = 20,
    ) {
    }
}

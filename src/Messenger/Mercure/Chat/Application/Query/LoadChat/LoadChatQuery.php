<?php

declare(strict_types=1);

namespace App\Messenger\Mercure\Chat\Application\Query\LoadChat;

use Symfony\Component\Uid\UuidV1;

final readonly class LoadChatQuery
{
    public function __construct(
        public UuidV1 $chatId,
    ) {
    }
}

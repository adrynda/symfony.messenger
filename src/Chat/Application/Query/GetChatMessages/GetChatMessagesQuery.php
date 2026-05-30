<?php

declare(strict_types=1);

namespace App\Chat\Application\Query\GetChatMessages;

use Symfony\Component\Uid\UuidV1;

final readonly class GetChatMessagesQuery
{
    public function __construct(
        public UuidV1 $chatId,
        public int $limit = 20,
        public int $page = 1,
    ) {
    }
}

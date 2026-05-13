<?php

declare(strict_types=1);

namespace App\Chat\Mercure\Domain\Event;

use App\Chat\_Shared\Domain\WriteModel\Message;

final readonly class SentMessageEvent
{
    public function __construct(
        public Message $message,
    ) {
    }
}

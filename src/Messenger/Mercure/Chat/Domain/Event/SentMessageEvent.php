<?php

declare(strict_types=1);

namespace App\Messenger\Mercure\Chat\Domain\Event;

use App\Messenger\_Shared\Domain\WriteModel\Message;

final readonly class SentMessageEvent
{
    public function __construct(
        public Message $message,
    ) {
    }
}

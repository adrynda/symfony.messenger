<?php

declare(strict_types=1);

namespace App\Mercure\Chat\Domain\Event;

use App\_Shared\Domain\WriteModel\Message;

final readonly class SentMessageEvent
{
    public function __construct(
        public Message $message,
    ) {
    }
}

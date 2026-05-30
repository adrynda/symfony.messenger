<?php

declare(strict_types=1);

namespace App\Chat\Domain\Event;

use App\Chat\Domain\Model\Message;

final readonly class SentMessageEvent
{
    public function __construct(
        public Message $message,
    ) {
    }
}

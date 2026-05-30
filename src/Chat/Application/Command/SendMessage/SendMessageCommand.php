<?php

declare(strict_types=1);

namespace App\Chat\Application\Command\SendMessage;

use Symfony\Component\Uid\UuidV1;

final readonly class SendMessageCommand
{
    public function __construct(
        public UuidV1 $chatId,
        public UuidV1 $userId,
        public string $content,
    ) {
    }
}

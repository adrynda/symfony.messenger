<?php

declare(strict_types=1);

namespace App\Messenger\Mercure\Chat\Domain\DTO;

use App\Messenger\_Shared\Domain\ReadModel\MessageView;
use App\Messenger\_Shared\Domain\WriteModel\Message;

final readonly class PublishMessageDTO extends PublishDTO
{
    public static function fromEntity(Message $message): static
    {
        return new self(
            $message->chat->id->toString(),
            MessageView::fromEntity($message)->toArray(),
        );
    }
}

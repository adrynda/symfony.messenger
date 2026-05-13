<?php

declare(strict_types=1);

namespace App\Chat\Mercure\Domain\DTO;

use App\Chat\_Shared\Domain\ReadModel\MessageView;
use App\Chat\_Shared\Domain\WriteModel\Message;

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

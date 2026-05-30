<?php

declare(strict_types=1);

namespace App\Chat\Domain\DTO;

use App\Chat\Domain\ViewModel\MessageView;
use App\Chat\Domain\Model\Message;

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

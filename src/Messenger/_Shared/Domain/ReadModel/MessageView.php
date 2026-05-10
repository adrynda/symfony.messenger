<?php

declare(strict_types=1);

namespace App\Messenger\_Shared\Domain\ReadModel;

use App\Messenger\_Shared\Domain\WriteModel\Message;
use DateTimeImmutable;
use Symfony\Component\Uid\UuidV1;

final readonly class MessageView
{
    public function __construct(
        public UuidV1 $id,
        public UuidV1 $userId,
        public UuidV1 $chatId,
        public DateTimeImmutable $sentAt,
        public string $content,
    ) {
    }

    public static function fromEntity(Message $entity): static
    {
        return new self(
            $entity->id,
            $entity->user->id,
            $entity->chat->id,
            $entity->sentAt,
            $entity->content,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->userId,
            'chat_id' => $this->chatId,
            'sent_at' => $this->sentAt,
            'content' => $this->content,
        ];
    }
}

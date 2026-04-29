<?php

declare(strict_types=1);

namespace App\_Shared\Domain\WriteModel;

use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV1;
use DateTimeImmutable;

class Message
{
    public function __construct(
        public readonly UuidV1 $id,
        public readonly User $user,
        public readonly Chat $chat,
        public readonly DateTimeImmutable $sentAt,
        public string $content,
    ) {
        $user->addMessage($this);
        $chat->addMessage($this);
    }

    public static function create(
        User $user,
        Chat $chat,
        string $content,
    ): self {
        return new self(
            Uuid::v1(),
            $user,
            $chat,
            new DateTimeImmutable(),
            $content,
        );
    }
}

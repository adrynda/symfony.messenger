<?php

declare(strict_types=1);

namespace App\Chat\_Shared\Domain\ReadModel;

use App\Chat\_Shared\Domain\WriteModel\Chat;
use Symfony\Component\Uid\UuidV1;

final readonly class ChatView
{
    public function __construct(
        public UuidV1 $id,
        /** @var UserView[] $users */
        public array $users,
        /** @var MessageView[] $messages */
        public array $messages = [],
    ) {}

    /** @deprecated messages will be limited to last n */
    public static function fromEntity(Chat $entity): static
    {
        return new self(
            $entity->id,
            $entity->getUsers()->map(UserView::fromEntity(...))->toArray(),
//            $entity->getMessages()->map(MessageView::fromEntity(...))->toArray(),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'users' => array_map(fn ($user) => $user->toArray(), $this->users),
            'messages' => array_map(fn ($message) => $message->toArray(), $this->messages),
        ];
    }
}

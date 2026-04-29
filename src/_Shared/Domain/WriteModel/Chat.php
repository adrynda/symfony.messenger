<?php

declare(strict_types=1);

namespace App\_Shared\Domain\WriteModel;

use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV1;

class Chat
{
    use MessagesTrait;

    public function __construct(
        public UuidV1 $id,
        /** @var User[] $users */
        private array $users,
        /** @var Message[] $messages */
        private array $messages = [],
    ) {}

    /** @var User[] $users */
    public static function create(array $users): self
    {
        return new self(
            Uuid::v1(),
            $users,
        );
    }

    public function getUsers(): array
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        $isFound = current(array_filter($this->users, fn (User $usr) => $usr->id === $user->id));

        if (!$isFound) {
            $this->users[] = $user;
        }

        return $this;
    }
}

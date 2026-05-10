<?php

declare(strict_types=1);

namespace App\Messenger\_Shared\Domain\ReadModel;

use App\Messenger\_Shared\Domain\WriteModel\User\User;
use Symfony\Component\Uid\UuidV1;

final readonly class UserView
{
    public function __construct(
        public readonly UuidV1 $id,
        public string $username,
        public string $avatar,
    ) {
    }

    public static function fromEntity(User $entity): static
    {
        return new self(
            $entity->id,
            $entity->username,
            $entity->avatar,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'avatar' => $this->avatar,
        ];
    }
}

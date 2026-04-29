<?php

declare(strict_types=1);

namespace App\_Shared\Domain\WriteModel;

use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV1;

class User
{
    use MessagesTrait;
    
    public function __construct(
        public readonly UuidV1 $id,
        public string $username,
        public string $avatar,
        /** @var Message[] $messages */
        private array $messages = [],
    ) {
    }

    public static function create(
        string $username,
        string $avatar,
    ): self {
        return new self(
            Uuid::v1(),
            $username,
            $avatar,
        );
    }
}

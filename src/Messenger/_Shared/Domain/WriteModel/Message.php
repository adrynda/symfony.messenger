<?php

declare(strict_types=1);

namespace App\Messenger\_Shared\Domain\WriteModel;

use App\Core\Domain\WriteModel\AbstractUuidEntity;
use App\Core\Domain\WriteModel\User\User;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV1;

#[ORM\Entity]
#[ORM\Table(name: 'messages')]
class Message extends AbstractUuidEntity
{
    private function __construct(
        UuidV1 $id,

        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'messages')]
        #[ORM\JoinColumn(nullable: false)]
        public readonly User $user,

        #[ORM\ManyToOne(targetEntity: Chat::class, inversedBy: 'messages')]
        #[ORM\JoinColumn(nullable: false)]
        public readonly Chat $chat,

        #[ORM\Column(type: 'datetime_immutable')]
        public readonly DateTimeImmutable $sentAt,

        #[ORM\Column(type: 'text')]
        public string $content,
    ) {
        parent::__construct($id);

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

<?php

declare(strict_types=1);

namespace App\Messenger\_Shared\Domain\WriteModel\User;

use App\Messenger\_Shared\Domain\WriteModel\AbstractUuidEntity;
use App\Messenger\_Shared\Domain\WriteModel\Chat;
use App\Messenger\_Shared\Domain\WriteModel\Message;
use App\Messenger\_Shared\Domain\WriteModel\Trait\MessagesTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV1;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User extends AbstractUuidEntity
{
    use MessagesTrait;

    private function __construct(
        UuidV1 $id,

        #[ORM\Column(type: 'text')]
        public string $username,

        #[ORM\Column(type: 'text')]
        public string $avatar,

        #[ORM\ManyToMany(targetEntity: Chat::class, mappedBy: 'users')]
        private Collection $chats,

        #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'user', cascade: ['persist', 'remove'], orphanRemoval: true)]
        private Collection $messages,

        #[ORM\Embedded(Credentials::class)]
        public readonly Credentials $credentials,
    ) {
        parent::__construct($id);
    }

    /**
     * @param Chat[] $chats
     * @param Message[] $messages
     */
    public static function create(
        string $username,
        string $avatar,
        string $email,
        string $password,
        array $chats = [],
        array $messages = [],
    ): self {
        return new self(
            Uuid::v1(),
            $username,
            $avatar,
            new ArrayCollection($chats),
            new ArrayCollection($messages),
            new Credentials(
                $email,
                $password,
            ),
        );
    }

    public function addChat(Chat $chat): self
    {
        if (!$this->chats->contains($chat)) {
            $this->chats->add($chat);
            $chat->addUser($this);
        }

        return $this;
    }
}

<?php

declare(strict_types=1);

namespace App\Messenger\_Shared\Domain\WriteModel;

use App\Core\Domain\WriteModel\AbstractUuidEntity;
use App\Core\Domain\WriteModel\User\User;
use App\Messenger\_Shared\Domain\WriteModel\Trait\MessagesTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV1;

#[ORM\Entity]
#[ORM\Table(name: 'chats')]
class Chat extends AbstractUuidEntity
{
    use MessagesTrait;

    private function __construct(
        UuidV1 $id,

        #[ORM\ManyToMany(targetEntity: User::class, fetch: 'EAGER', cascade: ['persist'])]
        #[ORM\JoinTable(name: 'chats_users')]
        private Collection $users,

        #[ORM\OneToMany(targetEntity: Message::class, fetch: 'EXTRA_LAZY', mappedBy: 'chat', cascade: ['persist', 'remove'], orphanRemoval: true)]
        private Collection $messages,
    ) {
        parent::__construct($id);

        foreach ($users as $user) {
            $user->addChat($this);
        }

//        foreach ($messages as $message) {
//            $message->addChat($this);
//        }
    }

    /**
     * @param User[] $users
     * @param Message[] $messages
     */
    public static function create(
        array $users,
        array $messages = [],
    ): self {
        return new self(
            Uuid::v1(),
            new ArrayCollection($users),
            new ArrayCollection($messages),
        );
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addChat($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->count() <= 2) {
            throw new \DomainException('There must be at least 2 users in chat.');
        }

        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }
}

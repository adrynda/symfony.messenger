<?php

declare(strict_types=1);

namespace App\Core\Domain\Model\User;

use App\Core\Domain\DTO\RegistrationDTO;
use App\Core\Domain\Model\AbstractUuidEntity;
use App\Chat\Domain\Model\Chat;
use App\Chat\Domain\Model\Message;
use App\Chat\Domain\Model\Trait\MessagesTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV1;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User extends AbstractUuidEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    use MessagesTrait;

    #[ORM\ManyToMany(targetEntity: Chat::class, mappedBy: 'users')]
    private Collection $chats;

    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'user', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $messages;

    private function __construct(
        UuidV1 $id,

        #[ORM\Column(type: 'text')]
        public string $username = '',

        #[ORM\Column(type: 'text', nullable: true)]
        public ?string $avatar = null,

        #[ORM\Embedded(Credentials::class)]
        public readonly Credentials $credentials = new Credentials(),

        array $chats = [],
        array $messages = [],
    ) {
        parent::__construct($id);
        $this->chats = new ArrayCollection($chats);
        $this->messages = new ArrayCollection($messages);
    }

    /**
     * @param Chat[] $chats
     * @param Message[] $messages
     */
    public static function create(
        string $username,
        ?string $avatar = null,
        string $email,
        ?string $password = null,
        array $chats = [],
        array $messages = [],
    ): self {
        return new self(
            id: Uuid::v1(),
            username: $username,
            avatar: $avatar,
            credentials: new Credentials(
                email: $email,
                password: $password,
            ),
            chats: $chats,
            messages: $messages,
        );
    }

    public static function register(RegistrationDTO $dto): self
    {
        return new self(
            id: Uuid::v1(),
            username: $dto->username,
            credentials: new Credentials(
                email: $dto->email,
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


    public function getPassword(): ?string
    {
        return $this->credentials->password;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
        return;
    }

    public function getUserIdentifier(): string
    {
        return $this->credentials->email;
    }

    public function __toString(): string
    {
        return $this->username;
    }
}

<?php

declare(strict_types=1);

namespace App\Messenger\Mercure\Chat\Application\Command\SendMessage;

use App\Messenger\_Shared\Domain\Repository\Chat\Write\ChatRepositoryInterface;
use App\Messenger\_Shared\Domain\WriteModel\Message;
use App\Messenger\_Shared\Domain\WriteModel\User\User;
use App\Messenger\Mercure\Chat\Domain\DTO\PublishMessageDTO;
use App\Messenger\Mercure\Chat\Domain\Event\SentMessageEvent;
use App\Messenger\Mercure\Chat\Domain\Service\PublisherInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final readonly class SendMessageHandler
{
    public function __construct(
        private PublisherInterface $publisher,
        private ChatRepositoryInterface $chatRepository,
        private MessageBusInterface $eventBus,
    ) {
    }

    public function __invoke(SendMessageCommand $command): void
    {
        $chat = $this->chatRepository->getById($command->chatId);
        $user = current(array_filter($chat->getUsers(), fn (User $user) => $user->id->equals($command->userId)));

        $message = Message::create(
            $user,
            $chat,
            $command->content,
        );

        $this->publisher->publish(PublishMessageDTO::fromEntity($message));

        $this->eventBus->dispatch(new SentMessageEvent($message));
    }
}

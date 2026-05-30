<?php

declare(strict_types=1);

namespace App\Chat\Application\Command\SendMessage;

use App\Core\Domain\Model\User\User;
use App\Chat\Domain\Repository\Chat\Write\ChatRepositoryInterface;
use App\Chat\Domain\Model\Chat;
use App\Chat\Domain\Model\Message;
use App\Chat\Domain\DTO\PublishMessageDTO;
use App\Chat\Domain\Event\SentMessageEvent;
use App\Chat\Domain\Service\PublisherInterface;
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
        /** @var Chat $chat */
        $chat = $this->chatRepository->find($command->chatId);
        $user = $chat->getUsers()->findFirst(fn (int $key, User $user) => $user->id->equals($command->userId));

        $message = Message::create(
            $user,
            $chat,
            $command->content,
        );

        $this->publisher->publish(PublishMessageDTO::fromEntity($message));

        $this->eventBus->dispatch(new SentMessageEvent($message));
    }
}

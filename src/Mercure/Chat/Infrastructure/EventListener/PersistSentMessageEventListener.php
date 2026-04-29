<?php

declare(strict_types=1);

namespace App\Mercure\Chat\Infrastructure\EventListener;

use App\_Shared\Domain\Repository\Chat\Write\ChatRepositoryInterface;
use App\Mercure\Chat\Domain\Event\SentMessageEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class PersistSentMessageEventListener
{
    public function __construct(
        private ChatRepositoryInterface $chatRepository,
    ) {
    }

    public function __invoke(SentMessageEvent $event): void
    {
        $chat = $this->chatRepository->getById($event->message->chat->id);
        $chat->addMessage($event->message);
        $this->chatRepository->save($chat);
    }
}

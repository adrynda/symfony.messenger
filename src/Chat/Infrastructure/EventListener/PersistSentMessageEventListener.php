<?php

declare(strict_types=1);

namespace App\Chat\Infrastructure\EventListener;

use App\Chat\Domain\Repository\Chat\Write\MessageRepositoryInterface;
use App\Chat\Domain\Event\SentMessageEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class PersistSentMessageEventListener
{
    public function __construct(
        private MessageRepositoryInterface $messageRepository,
    ) {
    }

    public function __invoke(SentMessageEvent $event): void
    {
        $this->messageRepository->save($event->message);
    }
}

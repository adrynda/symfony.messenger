<?php

declare(strict_types=1);

namespace App\Chat\Mercure\Application\Query\GetChatMessages;

use App\Chat\_Shared\Domain\ReadModel\MessageView;
use App\Chat\_Shared\Domain\Repository\Chat\Read\MessageRepositoryInterface;
use App\Chat\_Shared\Domain\WriteModel\Message;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetChatMessagesHandler
{
    public function __construct(
        private MessageRepositoryInterface $messageRepository,
    ) {
    }

    /**
     * @param GetChatMessagesQuery $query
     * @return MessageView[]
     */
    public function __invoke(GetChatMessagesQuery $query): array
    {
        $messages = $this->messageRepository->lastLimitedFromChat($query->chatId, $query->limit);
        usort($messages, fn (Message $a, Message $b) => $a->sentAt <=> $b->sentAt);

        return array_map(MessageView::fromEntity(...), $messages);
    }
}

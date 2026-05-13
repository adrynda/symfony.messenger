<?php

declare(strict_types=1);

namespace App\Chat\Mercure\Application\Query\LoadChat;

use App\Chat\_Shared\Domain\ReadModel\ChatView;
use App\Chat\_Shared\Domain\ReadModel\MessageView;
use App\Chat\_Shared\Domain\ReadModel\UserView;
use App\Chat\_Shared\Domain\Repository\Chat\Read\ChatRepositoryInterface;
use App\Chat\_Shared\Domain\Repository\Chat\Read\MessageRepositoryInterface;
use App\Chat\_Shared\Domain\WriteModel\Chat;
use App\Chat\_Shared\Domain\WriteModel\Message;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\UuidV1;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[AsMessageHandler]
final readonly class LoadChatQueryHandler
{
    public function __construct(
        private ChatRepositoryInterface $chatRepository,
        private MessageRepositoryInterface $messageRepository,
        private CacheInterface $cache,
    ) {
    }

    public function __invoke(LoadChatQuery $query): ChatView
    {
        $key = $this->getCacheKey($query->chatId);
        $this->cache->delete($key);

        return $this->cache->get($key, function (ItemInterface $item) use ($query) {
            $item->expiresAfter(60 * 60);

            /** @var Chat $chat */
            $chat = $this->chatRepository->find($query->chatId);
            $messages = $this->messageRepository->lastLimitedFromChat($query->chatId, $query->limit);
            usort($messages, fn (Message $a, Message $b) => $a->sentAt <=> $b->sentAt);

            return new ChatView(
                $chat->id,
                $chat->getUsers()->map(UserView::fromEntity(...))->toArray(),
                array_map(MessageView::fromEntity(...), $messages),
            );
        });
    }

    private function getCacheKey(UuidV1 $chatId): string
    {
        return 'chat_' . $chatId;
    }
}

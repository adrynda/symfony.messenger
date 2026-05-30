<?php

declare(strict_types=1);

namespace App\Chat\Application\Query\FindUserChats;

use App\Chat\Domain\ReadModel\ChatView;
use App\Chat\Domain\Repository\Chat\Read\UserChatRepositoryInterface;
use App\Chat\Domain\WriteModel\Chat;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class FindUserChatsHandler
{
    public function __construct(
        private UserChatRepositoryInterface $userChatRepository,
    ) {
    }

    /**
     * @return ChatView[]
     */
    public function __invoke(FindUserChatsQuery $query): array
    {
        $chats = $this->userChatRepository->findByUserId($query->userId);

        return array_map(fn(Chat $chat) => ChatView::fromEntity($chat), $chats);
    }
}

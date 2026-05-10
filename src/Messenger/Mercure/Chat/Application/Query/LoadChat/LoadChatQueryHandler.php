<?php

declare(strict_types=1);

namespace App\Messenger\Mercure\Chat\Application\Query\LoadChat;

use App\Messenger\_Shared\Domain\ReadModel\ChatView;
use App\Messenger\_Shared\Domain\Repository\Chat\Read\ChatRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class LoadChatQueryHandler
{
    public function __construct(
        private readonly ChatRepositoryInterface $chatrepository,
    ) {
    }

    public function __invoke(LoadChatQuery $query): ChatView
    {
        return $this->chatrepository->getById($query->chatId);
    }
}

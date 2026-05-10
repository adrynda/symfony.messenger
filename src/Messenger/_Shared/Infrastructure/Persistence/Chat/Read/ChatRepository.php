<?php

declare(strict_types=1);

namespace App\Messenger\_Shared\Infrastructure\Persistence\Chat\Read;

use App\Messenger\_Shared\DataFixtures\ChatFixture;
use App\Messenger\_Shared\Domain\ReadModel\ChatView;
use App\Messenger\_Shared\Domain\Repository\Chat\Read\ChatRepositoryInterface;
use Symfony\Component\Uid\UuidV1;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class ChatRepository implements ChatRepositoryInterface
{
    public function __construct(
        private readonly CacheInterface $cache,
    ) {
    }

    public function getById(UuidV1 $id): ChatView
    {
        $chat = $this->cache->get('chat ' . $id, function (ItemInterface $item) use ($id) {
            $item->expiresAfter(60 * 60);

            return ChatFixture::createChat($id);
        });

        return ChatView::fromEntity($chat);
    }
}

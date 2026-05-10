<?php

declare(strict_types=1);

namespace App\Messenger\_Shared\Infrastructure\Persistence\Chat\Write;

use App\Messenger\_Shared\DataFixtures\ChatFixture;
use App\Messenger\_Shared\Domain\Repository\Chat\Write\ChatRepositoryInterface;
use App\Messenger\_Shared\Domain\WriteModel\Chat;
use Symfony\Component\Uid\UuidV1;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class ChatRepository implements ChatRepositoryInterface
{
    public function __construct(
        private readonly CacheInterface $cache,
    ) {
    }

    public function getById(UuidV1 $id): Chat
    {
        $chat = $this->cache->get('chat ' . $id, function (ItemInterface $item) use ($id) {
            $item->expiresAfter(60 * 60);

            return ChatFixture::createChat($id);
        });

        return $chat;
    }

    public function save(Chat $chat): Chat
    {
        $key = 'chat ' . $chat->id;

        $this->cache->delete($key);

        return $this->cache->get($key, function (ItemInterface $item) use ($chat) {
            $item->expiresAfter(60 * 60);

            return $chat;
        });
    }
}

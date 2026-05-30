<?php

declare(strict_types=1);

namespace App\Chat\Infrastructure\EventListener;

use App\Chat\Domain\Model\Chat;
use App\Chat\Domain\Event\SentMessageEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\UuidV1;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[AsMessageHandler]
final readonly class CacheSentMessageEventListener
{
    public function __construct(
        private readonly CacheInterface $cache,
    ) {
    }

    public function __invoke(SentMessageEvent $event): void
    {
        // todo: tutaj coś
        $chat = $this->getChat($event->message->chat->id);
        $chat->addMessage($event->message);
        $this->saveChat($chat);
    }



    private function getChat(UuidV1 $id): Chat
    {
        dd('asd');
        return $this->cache->get('mercure ' . $id, function (ItemInterface $item) use ($id) {
            $item->expiresAfter(60 * 60);

            return ChatFixture::createChat($id);
        });
    }

    public function saveChat(Chat $chat): void
    {
        $key = 'mercure ' . $chat->id;

        $this->cache->delete($key);

        $this->cache->get($key, function (ItemInterface $item) use ($chat) {
            $item->expiresAfter(60 * 60);

            return $chat;
        });
    }
}

<?php

declare(strict_types=1);

namespace App\Messenger\_Shared\DataFixture;

use App\Messenger\_Shared\Domain\WriteModel\AbstractUuidEntity;
use App\Messenger\_Shared\Domain\WriteModel\Chat;
use App\Messenger\_Shared\Domain\WriteModel\Message;
use App\Messenger\_Shared\Domain\WriteModel\User\User;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use ReflectionClass;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV1;

class ChatFixture extends AbstractFixture
{
    public static function createChat(UuidV1 $chatId): Chat
    {
        $me = User::create(
            username: 'Jan Kowalski',
            avatar: 'https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3-bg.webp',
            email: 'jan.kowalski@test.local',
            password: '',
        );

        $other = User::create(
            username: 'Grażyna Kowalska',
            avatar: 'https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava4-bg.webp',
            email: 'grazyna.kowalska@test.local',
            password: '',
        );

        /** @var Chat $chat */
        $chat = self::createEntityWithDefaultId(Chat::class, $chatId);
        self::setReflectedPropertyValue($chat, 'users', new ArrayCollection([$me, $other]));
        self::setReflectedPropertyValue($chat, 'messages', new ArrayCollection());
        self::fillMessages($chat);

        return $chat;
    }

    private static function fillMessages(Chat $chat): void
    {
        $users = $chat->getUsers();
        $me = $users[0];
        $other = $users[1];
        $yesterday = new DateTimeImmutable('yesterday');

        $messages = [
            self::getMessageWithSetTime($me, $chat, 'elo elo', $yesterday),
            self::getMessageWithSetTime($me, $chat, '3 2 0', $yesterday->modify('+10 seconds')),
            self::getMessageWithSetTime($me, $chat, 'kope lat', $yesterday->modify('+20 seconds')),
            self::getMessageWithSetTime($other, $chat, 'siema', $yesterday->modify('+30 seconds')),
            self::getMessageWithSetTime($other, $chat, 'co tam?', $yesterday->modify('+40 seconds')),
            self::getMessageWithSetTime($me, $chat, 'git', $yesterday->modify('+50 seconds')),
            Message::create($me, $chat, 'jakie plany na weekend?'),
            Message::create($other, $chat, 'piwko'),
            Message::create($other, $chat, 'grill'),
            Message::create($other, $chat, 'kiełbaski'),
            Message::create($other, $chat, 'ogólnie chill bomba'),
            Message::create($me, $chat, 'naaaajs'),
        ];

        foreach ($messages as $message) {
            $chat->addMessage($message);
        }
    }

    private static function getMessageWithSetTime(
        User $user,
        Chat $chat,
        string $content,
        DateTimeInterface $sentAt,
    ): Message {
        /** @var Message $message */
        $message = self::createEntityWithDefaultId(Message::class, Uuid::v1());

        self::setReflectedPropertyValue($message, 'user', $user);
        self::setReflectedPropertyValue($message, 'chat', $chat);
        self::setReflectedPropertyValue($message, 'content', $content);
        self::setReflectedPropertyValue($message, 'sentAt', $sentAt);

        return $message;
    }
}

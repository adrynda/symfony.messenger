<?php

declare(strict_types=1);

namespace App\_Shared\DataFixture;

use Symfony\Component\Uid\Uuid;
// use App\_Shared\Entity\Chat;
// use App\_Shared\Entity\Message;
// use App\_Shared\Entity\User;
use App\_Shared\Domain\WriteModel\Chat;
use App\_Shared\Domain\WriteModel\Message;
use App\_Shared\Domain\WriteModel\User;
use DateTimeImmutable;
use Symfony\Component\Uid\UuidV1;

class ChatFixture
{
    public static function createChat(UuidV1 $chatId): Chat
    {
        $me = User::create(
            'Jan Kowalski',
            'https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3-bg.webp',
        );

        $other = User::create(
            'Grażyna Kowalska',
            'https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava4-bg.webp',
        );

        $chat = new Chat(
            $chatId,
            [$me, $other],
        );

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
            new Message(Uuid::v1(), $me, $chat, $yesterday, 'elo elo'),
            new Message(Uuid::v1(), $me, $chat, $yesterday->modify('+10 seconds'), '3 2 0'),
            new Message(Uuid::v1(), $me, $chat, $yesterday->modify('+20 seconds'), 'kope lat'),
            new Message(Uuid::v1(), $other, $chat, $yesterday->modify('+30 seconds'), 'siema'),
            new Message(Uuid::v1(), $other, $chat, $yesterday->modify('+40 seconds'), 'co tam?'),
            new Message(Uuid::v1(), $me, $chat, $yesterday->modify('+50 seconds'), 'git'),
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
}

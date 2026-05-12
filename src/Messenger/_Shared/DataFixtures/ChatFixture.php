<?php

declare(strict_types=1);

namespace App\Messenger\_Shared\DataFixtures;

use App\Core\Domain\WriteModel\User\User;
use App\Messenger\_Shared\Domain\WriteModel\Chat;
use App\Messenger\_Shared\Domain\WriteModel\Message;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class ChatFixture extends AbstractFixture
{
    use ReflectionFixturesTrait;

    private ObjectManager $manager;

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $chat = Chat::create([
            $this->createUser('Jan Kowalski', 'https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3-bg.webp'),
            $this->createUser('Grażyna Kowalska', 'https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava4-bg.webp'),
        ]);
        $this->manager->persist($chat);
        $this->fillMessages($chat);

        $this->manager->flush();
    }

    private function createUser(
        string $username,
        string $avatar,
    ): User {
        $user = User::create(
            username: $username,
            avatar: $avatar,
            email: strtolower(str_replace(' ', '.', $username)) . '@test.local',
            password: '',
        );

        $this->manager->persist($user);

        return $user;
    }

    private function fillMessages(Chat $chat): void
    {
        $users = $chat->getUsers();
        $yesterday = new DateTimeImmutable('yesterday');

        $messages = [
            $this->getMessageWithSetTime($users[0], $chat, 'elo elo', $yesterday),
            $this->getMessageWithSetTime($users[0], $chat, '3 2 0', $yesterday->modify('+10 seconds')),
            $this->getMessageWithSetTime($users[0], $chat, 'kope lat', $yesterday->modify('+20 seconds')),
            $this->getMessageWithSetTime($users[1], $chat, 'siema', $yesterday->modify('+30 seconds')),
            $this->getMessageWithSetTime($users[1], $chat, 'co tam?', $yesterday->modify('+40 seconds')),
            $this->getMessageWithSetTime($users[0], $chat, 'git', $yesterday->modify('+50 seconds')),
            Message::create($users[0], $chat, 'jakie plany na weekend?'),
            Message::create($users[1], $chat, 'piwko'),
            Message::create($users[1], $chat, 'grill'),
            Message::create($users[1], $chat, 'kiełbaski'),
            Message::create($users[1], $chat, 'ogólnie chill bomba'),
            Message::create($users[0], $chat, 'naaaajs'),
        ];

        foreach ($messages as $message) {
            $this->manager->persist($message);
            $chat->addMessage($message);
        }
    }

    private function getMessageWithSetTime(
        User $user,
        Chat $chat,
        string $content,
        DateTimeInterface $sentAt,
    ): Message {
        /** @var Message $message */
        $message = $this->createEntityWithDefaultId(Message::class, Uuid::v1());

        $this->setReflectedPropertyValue($message, 'user', $user);
        $this->setReflectedPropertyValue($message, 'chat', $chat);
        $this->setReflectedPropertyValue($message, 'content', $content);
        $this->setReflectedPropertyValue($message, 'sentAt', $sentAt);

        return $message;
    }
}

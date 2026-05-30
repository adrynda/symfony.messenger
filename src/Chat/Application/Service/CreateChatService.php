<?php

namespace App\Chat\Application\Service;

use App\Chat\Application\Exception\ChatAlreadyExistsException;
use App\Chat\Domain\Repository\Chat\Read\ChatFinderInterface;
use App\Chat\Domain\Repository\Chat\Write\ChatRepositoryInterface;
use App\Chat\Domain\Model\Chat;
use App\Core\Domain\Repository\Read\UserRepositoryInterface;
use App\Core\Domain\Model\User\User;
use Doctrine\ORM\EntityNotFoundException;
use RuntimeException;

class CreateChatService
{
    public function __construct(
        private readonly ChatRepositoryInterface $chatRepository,
        private readonly ChatFinderInterface $chatFinder,
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function createChat(CreateChatDTO $DTO, ?User $user = null): Chat
    {
        $usersIds = array_filter([
            ...array_map(fn (User $user) => $user->id, $DTO->users->toArray()),
            $user?->id,
        ]);

        if (count($usersIds) < 2) {
            throw new RuntimeException('Not enough users');
        }

        $users = $this->userRepository->findBy(['id' => $usersIds]);

        if (empty($users)) {
            throw new EntityNotFoundException('Users not found');
        }

        if (count($users) !== count($usersIds)) {
            throw new EntityNotFoundException('Not all users were found');
        }

        $chat = $this->chatFinder->findByParticipants($usersIds);
        if (!empty($chat)) {
            throw new ChatAlreadyExistsException(chat: $chat);
        }

        $chat = Chat::create($users);
        $this->chatRepository->save($chat);

        return $chat;
    }
}

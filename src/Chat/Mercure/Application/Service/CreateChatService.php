<?php

namespace App\Chat\Mercure\Application\Service;

use App\Chat\_Shared\Domain\Repository\Chat\Write\ChatRepositoryInterface;
use App\Chat\_Shared\Domain\WriteModel\Chat;
use App\Core\Domain\Repository\Read\UserRepositoryInterface;
use App\Core\Domain\WriteModel\User\User;
use Doctrine\ORM\EntityNotFoundException;
use RuntimeException;

class CreateChatService
{
    public function __construct(
        private readonly ChatRepositoryInterface $chatRepository,
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function createChat(CreateChatDTO $DTO, ?User $user = null): Chat
    {
        $usersIds = array_filter([...$DTO->users, $user?->id]);

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

        $chat = Chat::create($users);
        $this->chatRepository->save($chat);

        return $chat;
    }
}

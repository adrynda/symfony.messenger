<?php

declare(strict_types=1);

namespace App\Chat\Application\Query\GetUserFriendList;

use App\Chat\Domain\ViewModel\UserView;
use App\Core\Domain\Repository\Read\UserRepositoryInterface;
use App\Core\Domain\Model\User\User;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetUserFriendListHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {
    }

    /**
     * @return UserView[]
     */
    public function __invoke(GetUserFriendListQuery $query): array
    {
        $results = $this->userRepository->findAll();

        return array_map(fn(User $user) => UserView::fromEntity($user), $results);
    }
}

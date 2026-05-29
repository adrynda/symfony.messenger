<?php

declare(strict_types=1);

namespace App\Core\Application\Query\IsEmailTaken;

use App\Core\Domain\Repository\Read\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class IsEmailTakenHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function __invoke(IsEmailTakenQuery $query): bool
    {
        $user = $this->userRepository->findOneBy(['credentials.email' => $query->email]);

        return !empty($user);
    }
}

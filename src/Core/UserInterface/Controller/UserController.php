<?php

declare(strict_types=1);

namespace App\Core\UserInterface\Controller;

use App\Chat\Domain\ReadModel\UserView;
use App\Core\Domain\WriteModel\User\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/core/user', name: 'core_user_')]
final class UserController extends AbstractCoreController
{
    #[Route('/me', name: 'current', methods: ['GET'])]
    public function me(): JsonResponse
    {
        /** @var ?User $currentUser */
        $currentUser = $this->getUser();
        return $this->json(UserView::fromEntity($currentUser));
    }
}

<?php

declare(strict_types=1);

namespace App\Core\UserInterface\Controller;

use App\Chat\_Shared\Domain\ReadModel\UserView;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/core/user', name: 'core_user_')]
final class UserController extends AbstractController
{
    #[Route('/me', name: 'current', methods: ['GET'])]
    public function me(): JsonResponse
    {
        return $this->json(UserView::fromEntity($this->getUser()));
    }
}

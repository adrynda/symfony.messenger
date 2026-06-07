<?php

declare(strict_types=1);

namespace App\Chat\UserInterface\Controller\Chat;

use App\Chat\Domain\Enum\ChatRoleEnum;
use App\Chat\UserInterface\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/chat', name: 'chat_create', methods: ['GET'])]
#[IsGranted(ChatRoleEnum::RoleCreateChat->value)]
final class CreateChatController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('@Chat/chat/create/index.html.twig');
    }
}

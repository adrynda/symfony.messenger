<?php

declare(strict_types=1);

namespace App\Chat\UserInterface\Controller\Chat;

use App\Chat\UserInterface\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/chat', name: 'chat_create', methods: ['GET'])]
final class CreateChatController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('chat/create_form.html.twig');
    }
}

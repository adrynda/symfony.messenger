<?php

declare(strict_types=1);

namespace App\Chat\UserInterface\Controller\Chat;

use App\Chat\Application\Service\CreateChatDTO;
use App\Chat\Application\Service\CreateChatService;
use App\Chat\UserInterface\AbstractController;
use App\Chat\UserInterface\Form\CreateChatType;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/chat/mercure/chat', name: 'chat_mercure_chat_create')]
final class CreateChatController extends AbstractController
{
    use HandleTrait;

    private MessageBusInterface $messageBus;

    #[Route(name: '', methods: ['GET', 'POST'])]
    public function create(
        Request $request,
        #[Autowire(service: 'core.registration.command.bus')]
        MessageBusInterface $commandBus,
        CreateChatService $createChatService,
    ): Response {
        $form = $this->createForm(CreateChatType::class, new CreateChatDTO, [
            'current_user_id' => $this->getUser()->id,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chat = $createChatService->createChat($form->getData(), $this->getUser());

            return $this->redirectToRoute('chat_mercure_chat_view', ['id' => $chat->id]);
        }

        return $this->render('chat/mercure/chat/form.html.twig', ['form' => $form]);
    }
}

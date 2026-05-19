<?php

declare(strict_types=1);

namespace App\Chat\Mercure\UserInterface\Controller\Chat;

use App\Chat\Mercure\Application\Query\GetUserFriendList\GetUserFriendListQuery;
use App\Chat\Mercure\Application\Service\CreateChatDTO;
use App\Chat\Mercure\Application\Service\CreateChatService;
use App\Chat\Mercure\UserInterface\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/chat/mercure/chat', name: 'chat_mercure_chat')]
final class CreateChatController extends AbstractController
{
    use HandleTrait;

    private MessageBusInterface $messageBus;

    #[Route(name: '_form', methods: ['GET'])]
    public function form(
        #[Autowire(service: 'chat.mercure.query.bus')]
        MessageBusInterface $queryBus,
    ): Response {
        $this->messageBus = $queryBus;
        $users = $this->handle(new GetUserFriendListQuery($this->getUser()->id));

        return $this->render('chat/mercure/chat/form.html.twig', [
            'users' => $users,
            'csrfTokenKey' => $this->getCsrfTokenKey(),
        ]);
    }

    #[Route(name: '_create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload]
        CreateChatDTO $createChatDTO,
        CreateChatService $createChatService,
    ): RedirectResponse {
        $this->validCsrfToken();

        $chat = $createChatService->createChat($createChatDTO, $this->getUser());

        return $this->redirectToRoute('chat_mercure_chat_view', ['id' => $chat->id]);
    }

    protected function getCsrfTokenKey(): string
    {
        return 'chat_mercure_chat_create';
    }
}

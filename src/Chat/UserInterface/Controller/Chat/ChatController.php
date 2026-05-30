<?php

declare(strict_types=1);

namespace App\Chat\UserInterface\Controller\Chat;

use App\Chat\Domain\ViewModel\ChatView;
use App\Chat\Domain\ViewModel\UserView;
use App\Chat\Domain\Model\Chat;
use App\Chat\Application\Query\GetChatMessages\GetChatMessagesQuery;
use App\Chat\UserInterface\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/chat/mercure/chat/{id}/asd', name: 'chat_mercure_chat')]
final class ChatController extends AbstractController
{
    use HandleTrait;

    private MessageBusInterface $messageBus;

    #[Route(name: '_view', methods: ['GET'])]
    public function view(
//        UuidV1 $chatId,
        Chat $chat,
        #[Autowire(service: 'chat.query.bus')]
        MessageBusInterface $queryBus,
    ): Response {
        $this->messageBus = $queryBus;
        $chatView = ChatView::fromEntity($chat);
        $messages = $this->handle(new GetChatMessagesQuery($chat->id));
        $chatView = $chatView->withMessages($messages);
//        dd($chat);

        UserView::fromEntity($this->getUser());
        return $this->render('chat/mercure/chat/view.html.twig', [
            'chat' => $chatView->withMessages($messages),
//            'csrfTokenKey' => $this->getCsrfTokenKey(),
        ]);
    }

//    #[Route(name: '_create', methods: ['POST'])]
//    public function create(
//        #[MapRequestPayload]
//        CreateChatDTO $createChatDTO,
//        CreateChatService $createChatService,
//    ): RedirectResponse {
//        $this->validCsrfToken();
//
//        $chat = $createChatService->createChat($createChatDTO, $this->getUser());
//
//        return $this->redirectToRoute('chat_mercure_chat', ['id' => $chat->id]);
//    }
//
//    protected function getCsrfTokenKey(): string
//    {
//        return 'chat_mercure_chat_message';
//    }
}

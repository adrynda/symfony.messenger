<?php

declare(strict_types=1);

namespace App\Chat\Mercure\UserInterface\Controller\Chat;

use App\Chat\_Shared\Domain\ReadModel\ChatView;
use App\Chat\_Shared\Domain\ReadModel\UserView;
use App\Chat\_Shared\Domain\WriteModel\Chat;
use App\Chat\Mercure\Application\Query\GetChatMessages\GetChatMessagesQuery;
use App\Chat\Mercure\UserInterface\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/chat/mercure/chat/{id}', name: 'chat_mercure_chat_open')]
final class OpenChatController extends AbstractController
{
    use HandleTrait;

    private MessageBusInterface $messageBus;

    #[Route(name: '', methods: ['GET'])]
    public function open(
//        UuidV1 $chatId,
        Chat $chat,
        #[Autowire(service: 'chat.mercure.query.bus')]
        MessageBusInterface $queryBus,
    ): Response {
        $this->messageBus = $queryBus;
        $chatView = ChatView::fromEntity($chat);
        $messages = $this->handle(new GetChatMessagesQuery($chat->id));
        $chatView = $chatView->withMessages($messages);
        dd($chatView);

        UserView::fromEntity($this->getUser());
        return $this->render('chat/mercure/chat/open.html.twig', [
            'chat' => $chatView->withMessages($messages),
//            'csrfTokenKey' => $this->getCsrfTokenKey(),
        ]);
    }
}

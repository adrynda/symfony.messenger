<?php

declare(strict_types=1);

namespace App\Chat\Mercure\UserInterface\Controller\Chats;

use App\Chat\Mercure\Application\Query\FindUserChats\FindUserChatsQuery;
use App\Chat\Mercure\UserInterface\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/chat/mercure/chats', name: 'chat_mercure_chats')]
final class GetChatsListController extends AbstractController
{
    use HandleTrait;

    private MessageBusInterface $messageBus;

    #[Route(name: '_list', methods: ['GET'])]
    public function list(
        #[Autowire(service: 'chat.mercure.query.bus')]
        MessageBusInterface $queryBus,
    ): Response {
        $this->messageBus = $queryBus;
        $chats = $this->handle(new FindUserChatsQuery($this->getUser()->id));

        return $this->render('chat/mercure/chats/list.html.twig', [
            'chats' => $chats,
        ]);
    }
}

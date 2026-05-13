<?php

declare(strict_types=1);

namespace App\Chat\Mercure\UserInterface\Controller;

use App\Chat\Mercure\Application\Command\SendMessage\SendMessageCommand;
use App\Chat\Mercure\Application\Query\GetUserFriendList\GetUserFriendListQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV1;

#[Route('/chat/mercure/list', name: 'chat_mercure_list')]
final class ChatListController extends AbstractController
{
    use HandleTrait;

    private MessageBusInterface $messageBus;

    #[Route('/load', name: '_load', methods: ['GET'])]
    public function load(
        #[Autowire(service: 'chat.mercure.query.bus')]
        MessageBusInterface $queryBus,
    ): Response {
        $this->messageBus = $queryBus;
        $chats = $this->handle(new GetUserFriendListQuery($this->getUser()->id));

        return $this->render('chat/mercure/list/load.html.twig', [
            'chats' => $chats,
        ]);
    }
}

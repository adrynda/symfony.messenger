<?php

declare(strict_types=1);

namespace App\Chat\UserInterface\Controller\Chats;

use App\Chat\Application\Query\FindUserChats\FindUserChatsQuery;
use App\Chat\Domain\Enum\ChatRoleEnum;
use App\Chat\UserInterface\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/chat/mercure/chats', name: 'chat_mercure_chats')]
#[IsGranted(ChatRoleEnum::RoleListChats->value)]
final class GetChatsListController extends AbstractController
{
    use HandleTrait;

    private MessageBusInterface $messageBus;

    #[Route(name: '_list', methods: ['GET'])]
    public function list(
        #[Autowire(service: 'chat.query.bus')]
        MessageBusInterface $queryBus,
    ): Response {
        $this->messageBus = $queryBus;
        $chats = $this->handle(new FindUserChatsQuery($this->getUser()->id));

        return $this->render('@Chat/chat/list/index.html.twig', [
            'chats' => $chats,
        ]);
    }
}

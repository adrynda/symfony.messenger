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

#[Route('/chat/mercure', name: 'chat_mercure')]
final class ChatController extends AbstractController
{
    use HandleTrait;

    private MessageBusInterface $messageBus;

    #[Route('/load/{id}', name: '_load', methods: ['GET'])]
    public function load(
        UuidV1 $id,
        #[Autowire(service: 'chat.mercure.query.bus')]
        MessageBusInterface $queryBus,
    ): Response {
        $this->messageBus = $queryBus;
        $chat = $this->handle(new GetUserFriendListQuery($id, 5));

        $response = $this->render('mercure/mercure/index.html.twig', [
            'mercure' => $chat,
            'user' => $chat->users[array_rand($chat->users)],
        ]);

        return $response;
    }

    #[Route('/send-message', name: '_send_message', methods: ['POST'])]
    public function sendMessage(
        Request $request,
        #[Autowire(service: 'chat.mercure.command.bus')]
        MessageBusInterface $commandBus,
    ): JsonResponse {
        $payload = $request->getPayload();

        $commandBus->dispatch(
            new SendMessageCommand(
                UuidV1::fromString($payload->get('chat_id')),
                UuidV1::fromString($payload->get('user_id')),
                $payload->get('content'),
            ),
        );

        return new JsonResponse([
            'success' => true,
        ]);
    }
}

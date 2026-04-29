<?php

declare(strict_types=1);

namespace App\Mercure\Chat\UserInterface\Controller;

use App\Mercure\Chat\Application\Command\SendMessage\SendMessageCommand;
use App\Mercure\Chat\Application\Query\LoadChat\LoadChatQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV1;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;


#[Route('/mercure/chat', name: 'mercure_chat')]
final class ChatController extends AbstractController
{
    use HandleTrait;
    
    private MessageBusInterface $messageBus;

    #[Route('/load/{id?}', name: '_load', methods: ['GET'])]
    public function load(
        ?UuidV1 $id = null,
        #[Autowire(service: 'mercure.chat.query.bus')]
        MessageBusInterface $queryBus,
    ): Response {
        $this->messageBus = $queryBus;
        $chat = $this->handle(new LoadChatQuery($id ?: Uuid::v1()));

        if (empty($id)) {
            return $this->redirect('/mercure/chat/load/' . $chat->id->toString());
        }

        $response = $this->render('mercure/chat/index.html.twig', [
            'chat' => $chat,
            'user' => $chat->users[array_rand($chat->users)],
        ]);

        return $response;
    }

    #[Route('/send-message', name: '_send_message', methods: ['POST'])]
    public function sendMessage(
        Request $request,
        #[Autowire(service: 'mercure.chat.command.bus')]
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

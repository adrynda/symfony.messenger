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

#[Route('/chat/mercure/create', name: 'chat_mercure_create')]
final class ChatCreateController extends AbstractController
{
    use HandleTrait;

    private MessageBusInterface $messageBus;

    #[Route('/', name: '_view', methods: ['GET'])]
    public function view(
        #[Autowire(service: 'chat.mercure.query.bus')]
        MessageBusInterface $queryBus,
    ): Response {
        $this->messageBus = $queryBus;
        $users = $this->handle(new GetUserFriendListQuery($this->getUser()->id));

        return $this->render('chat/mercure/create/form.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/', name: '_post', methods: ['POST'])]
    public function create(
        Request $request,
        #[Autowire(service: 'chat.mercure.command.bus')]
        MessageBusInterface $commandBus,
    ): JsonResponse {
        $payload = $request->getPayload();
        dd($payload);

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

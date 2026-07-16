<?php

declare(strict_types=1);

namespace App\Core\UserInterface\Controller;

use App\Core\Application\Command\ActivateUser\ActivateUserCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\UuidV1;

#[Route('/panel/activate/{userTokenId}', name: 'core_activate', methods: ['GET'])]
final class ActivateController extends AbstractController
{
    public function __invoke(UuidV1 $userTokenId, MessageBusInterface $messageBus): Response
    {
        if (!empty($this->getUser())) {
            return $this->redirectToRoute('core_home_view');
        }

        $messageBus->dispatch(new ActivateUserCommand($userTokenId));

        return $this->redirectToRoute('core_login');
    }
}

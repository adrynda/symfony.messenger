<?php

namespace App\Core\Application\Event\UserWasRegistered;

use App\Core\Application\Command\SendUserActivationMail\SendUserActivationMailCommand;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final readonly class UserWasRegisteredListener
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function __invoke(UserWasRegisteredEvent $event): void
    {
        $this->messageBus->dispatch(new SendUserActivationMailCommand($event->user->getUserIdentifier()));
    }
}

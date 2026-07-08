<?php

namespace App\Core\Application\Command\SendUserActivationMail;

use App\Core\Domain\Model\User\User;
use App\Core\Domain\Repository\Write\UserRepositoryInterface;
use App\Core\Domain\Service\Mailer\RegisteredUserActivationMailer\RegisteredUserActivationMailerDTO;
use App\Core\Domain\Service\Mailer\RegisteredUserActivationMailer\RegisteredUserActivationMailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SendUserActivationMailHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private RegisteredUserActivationMailerInterface $mailer,
    ) {}

    public function __invoke(SendUserActivationMailCommand $command): void
    {
        /** @var User $user */
        $user = $this->userRepository->findOneBy(['credentials.email' => $command->userIdentifier]);

        if (!$user) {
            throw new \RuntimeException('exception.user.not_found');
        }

        $this->mailer->send(new RegisteredUserActivationMailerDTO(
            email: $user->credentials->email,
            username: $user->username,
            activationLink: $user->id,
        ));
    }
}

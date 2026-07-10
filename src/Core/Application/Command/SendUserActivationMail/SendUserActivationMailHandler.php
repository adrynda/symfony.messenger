<?php

namespace App\Core\Application\Command\SendUserActivationMail;

use App\Core\Domain\Enum\UserTokenTypeEnum;
use App\Core\Domain\Model\User\User;
use App\Core\Domain\Model\UserToken;
use App\Core\Domain\Repository\Write\UserRepositoryInterface;
use App\Core\Domain\Repository\Write\UserTokenRepositoryInterface;
use App\Core\Domain\Service\Mailer\RegisteredUserActivationMailer\RegisteredUserActivationMailerDTO;
use App\Core\Domain\Service\Mailer\RegisteredUserActivationMailer\RegisteredUserActivationMailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Routing\Generator\UrlGenerator;

#[AsMessageHandler]
final readonly class SendUserActivationMailHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private RegisteredUserActivationMailerInterface $mailer,
        private UserTokenRepositoryInterface $userTokenRepository,
        private UrlGenerator $urlGenerator,
    ) {}

    public function __invoke(SendUserActivationMailCommand $command): void
    {
        /** @var User $user */
        $user = $this->userRepository->findOneBy(['credentials.email' => $command->userIdentifier]);

        if (!$user) {
            throw new \RuntimeException('exception.user.not_found');
        }

        $userToken = UserToken::create(
            enum: UserTokenTypeEnum::Activation,
            user: $user,
        );
        $this->userTokenRepository->save($userToken);

        $this->mailer->send(new RegisteredUserActivationMailerDTO(
            email: $user->credentials->email,
            username: $user->username,
            activationLink: $this->urlGenerator->generate('activation', ['token' => $userToken->id]),
        ));
    }
}

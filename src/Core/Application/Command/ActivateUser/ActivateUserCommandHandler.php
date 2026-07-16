<?php

declare(strict_types=1);

namespace App\Core\Application\Command\ActivateUser;

use App\Core\Domain\Enum\UserTokenTypeEnum;
use App\Core\Domain\Model\UserToken;
use App\Core\Domain\Repository\Read\UserTokenRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class ActivateUserCommandHandler
{
    public function __construct(
        private UserTokenRepositoryInterface $userTokenRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(ActivateUserCommand $command): void
    {
        /** @var UserToken $userToken */
        $userToken = $this->userTokenRepository->find($command->userTokenId);

        if (!$userToken || $userToken->type !== UserTokenTypeEnum::Activation) {
            throw new \Exception('User token not found');
        }

        if (null !== $userToken->expiresAt && $userToken->expiresAt < new \DateTime()) {
            throw new \Exception('User token expired');
        }

        $user = $userToken->user;
        $user->credentials->active = true;

        $this->entityManager->remove($userToken);
        $this->entityManager->flush();
    }
}

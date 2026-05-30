<?php

namespace App\Core\Application\Command\RegisterUser;

use App\Core\Domain\DTO\RegistrationDTO;
use App\Core\Domain\Model\User\User;
use App\Core\Domain\Repository\Write\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsMessageHandler]
class RegisterUserHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {}

    public function __invoke(RegisterUserCommand $command): void
    {
        $this->checkUserExists($command->registrationDTO);

        $user = User::register($command->registrationDTO);
        $user->credentials->password = $this->passwordHasher->hashPassword(
            $user,
            $command->registrationDTO->password,
        );

        $this->userRepository->save($user);
    }

    private function checkUserExists(RegistrationDTO $dto): void
    {
        if (!empty($this->userRepository->findBy(['credentials.email' => $dto->email]))) {
            throw new \DomainException('User already exists');
        }
    }
}

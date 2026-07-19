<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Security;

use App\Core\Domain\Model\User\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class CoreUserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if ($user instanceof User && !$user->credentials->active) {
            throw new CustomUserMessageAccountStatusException('security.user.inactive');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
    }
}

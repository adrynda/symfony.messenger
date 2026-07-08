<?php

namespace App\Core\Application\Event\UserWasRegistered;

use App\Core\Domain\Model\User\User;

final readonly class UserWasRegisteredEvent
{
    public function __construct(
        public User $user,
    ) {
    }
}

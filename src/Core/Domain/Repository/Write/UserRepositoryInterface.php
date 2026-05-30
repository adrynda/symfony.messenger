<?php

declare(strict_types=1);

namespace App\Core\Domain\Repository\Write;

use App\Core\Domain\Model\User\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;
}

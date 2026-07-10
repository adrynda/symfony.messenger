<?php

declare(strict_types=1);

namespace App\Core\Domain\Repository\Write;

use App\Core\Domain\Model\UserToken;

interface UserTokenRepositoryInterface
{
    public function save(UserToken $userToken): void;
}

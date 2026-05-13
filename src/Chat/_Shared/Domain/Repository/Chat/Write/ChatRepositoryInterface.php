<?php

declare(strict_types=1);

namespace App\Chat\_Shared\Domain\Repository\Chat\Write;

use App\Chat\_Shared\Domain\WriteModel\Chat;

interface ChatRepositoryInterface
{
    public function save(Chat $chat): void;
}

<?php

declare(strict_types=1);

namespace App\Messenger\_Shared\Domain\Repository\Chat\Write;

use App\Messenger\_Shared\Domain\WriteModel\Chat;

interface ChatRepositoryInterface
{
    public function save(Chat $chat): void;
}

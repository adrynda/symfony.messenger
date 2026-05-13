<?php

declare(strict_types=1);

namespace App\Chat\_Shared\Domain\Repository\Chat\Write;

use App\Chat\_Shared\Domain\WriteModel\Message;

interface MessageRepositoryInterface
{
    public function save(Message $user): void;
}

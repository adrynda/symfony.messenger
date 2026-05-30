<?php

declare(strict_types=1);

namespace App\Chat\Domain\Repository\Chat\Write;

use App\Chat\Domain\WriteModel\Message;

interface MessageRepositoryInterface
{
    public function save(Message $user): void;
}

<?php

declare(strict_types=1);

namespace App\Messenger\_Shared\Domain\Repository\Chat\Read;

use App\Messenger\_Shared\Domain\WriteModel\Message;
use Symfony\Component\Uid\UuidV1;

interface MessageRepositoryInterface
{
    /**
     * @return Message[]
     */
    public function lastLimitedFromChat(UuidV1 $chatId, int $limit): array;
}

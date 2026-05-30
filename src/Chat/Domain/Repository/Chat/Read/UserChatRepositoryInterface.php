<?php

declare(strict_types=1);

namespace App\Chat\Domain\Repository\Chat\Read;

use Symfony\Component\Uid\UuidV1;

interface UserChatRepositoryInterface
{
    public function findByUserId(UuidV1 $userId): array;
}

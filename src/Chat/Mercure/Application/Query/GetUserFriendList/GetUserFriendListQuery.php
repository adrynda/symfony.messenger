<?php

declare(strict_types=1);

namespace App\Chat\Mercure\Application\Query\GetUserFriendList;

use Symfony\Component\Uid\UuidV1;

final readonly class GetUserFriendListQuery
{
    public function __construct(
        public UuidV1 $userId,
    ) {
    }
}

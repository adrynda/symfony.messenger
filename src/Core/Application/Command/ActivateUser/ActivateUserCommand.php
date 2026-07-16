<?php

declare(strict_types=1);

namespace App\Core\Application\Command\ActivateUser;

use Symfony\Component\Uid\UuidV1;

final readonly class ActivateUserCommand
{
    public function __construct(
        public UuidV1 $userTokenId,
    ) {
    }
}

<?php

namespace App\Chat\Mercure\Application\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Uid\UuidV1;

class CreateChatDTO
{
    public function __construct(
        /** @var UuidV1[] $users */
        public ?ArrayCollection $users = null,
    ) {}
}

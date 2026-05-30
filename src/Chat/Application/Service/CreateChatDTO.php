<?php

namespace App\Chat\Application\Service;

use App\Core\Domain\Model\User\User;
use Doctrine\Common\Collections\ArrayCollection;

class CreateChatDTO
{
    public function __construct(
        /** @var User[] $users */
        public ?ArrayCollection $users = null,
    ) {}
}

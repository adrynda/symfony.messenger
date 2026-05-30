<?php

declare(strict_types=1);

namespace App\Chat\Domain\Repository\Chat\Write;

use App\Chat\Domain\Model\Chat;

interface ChatRepositoryInterface
{
    public function save(Chat $chat): void;
}

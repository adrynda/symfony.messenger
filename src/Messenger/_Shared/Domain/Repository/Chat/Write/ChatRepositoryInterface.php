<?php

declare(strict_types=1);

namespace App\Messenger\_Shared\Domain\Repository\Chat\Write;

use App\Messenger\_Shared\Domain\WriteModel\Chat;
use Symfony\Component\Uid\UuidV1;

interface ChatRepositoryInterface
{
    public function getById(UuidV1 $id): Chat;

    public function save(Chat $chat): Chat;
}

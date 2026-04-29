<?php

declare(strict_types=1);

namespace App\_Shared\Domain\Repository\Chat\Read;

use App\_Shared\Domain\ReadModel\ChatView;
use Symfony\Component\Uid\UuidV1;

interface ChatRepositoryInterface
{
    public function getById(UuidV1 $id): ChatView;
}

<?php

declare(strict_types=1);

namespace App\Chat\Domain\Repository\Chat\Read;

use App\Chat\Domain\Model\Chat;
use Symfony\Component\Uid\UuidV1;

interface ChatFinderInterface
{
    /**
     * @param UuidV1[] $usersIds
     */
    public function findByParticipants(array $usersIds): ?Chat;
}

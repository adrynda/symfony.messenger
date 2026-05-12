<?php

declare(strict_types=1);

namespace App\Messenger\_Shared\Infrastructure\Persistence\Chat\Read;

use App\Messenger\_Shared\Domain\Repository\Chat\Read\ChatRepositoryInterface;
use App\Messenger\_Shared\Domain\WriteModel\Chat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class ChatRepository extends ServiceEntityRepository implements ChatRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chat::class);
    }
}

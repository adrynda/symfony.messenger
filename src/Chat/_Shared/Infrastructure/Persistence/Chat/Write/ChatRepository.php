<?php

declare(strict_types=1);

namespace App\Chat\_Shared\Infrastructure\Persistence\Chat\Write;

use App\Chat\_Shared\Domain\Repository\Chat\Write\ChatRepositoryInterface;
use App\Chat\_Shared\Domain\WriteModel\Chat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class ChatRepository extends ServiceEntityRepository implements ChatRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chat::class);
    }

    public function save(Chat $chat): void
    {
        $em = $this->getEntityManager();
        $em->persist($chat);
        $em->flush();
    }
}

<?php

declare(strict_types=1);

namespace App\Chat\_Shared\Infrastructure\Persistence\Chat\Write;

use App\Chat\_Shared\Domain\Repository\Chat\Write\MessageRepositoryInterface;
use App\Chat\_Shared\Domain\WriteModel\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class MessageRepository extends ServiceEntityRepository implements MessageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function save(Message $user): void
    {
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();
    }
}

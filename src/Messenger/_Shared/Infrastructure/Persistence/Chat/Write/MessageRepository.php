<?php

declare(strict_types=1);

namespace App\Messenger\_Shared\Infrastructure\Persistence\Chat\Write;

use App\Messenger\_Shared\Domain\Repository\Chat\Write\MessageRepositoryInterface;
use App\Messenger\_Shared\Domain\WriteModel\Message;
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

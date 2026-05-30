<?php

declare(strict_types=1);

namespace App\Chat\Infrastructure\Persistence\Chat\Write;

use App\Chat\Domain\Repository\Chat\Write\MessageRepositoryInterface;
use App\Chat\Domain\Model\Message;
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

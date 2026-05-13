<?php

declare(strict_types=1);

namespace App\Chat\_Shared\Infrastructure\Persistence\Chat\Read;

use App\Chat\_Shared\Domain\Repository\Chat\Read\ChatRepositoryInterface;
use App\Chat\_Shared\Domain\Repository\Chat\Read\UserChatRepositoryInterface;
use App\Chat\_Shared\Domain\WriteModel\Chat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\UuidV1;

final class ChatRepository extends ServiceEntityRepository implements ChatRepositoryInterface, UserChatRepositoryInterface
{
    private const ALIAS = 'c';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chat::class);
    }

    public function findByUserId(UuidV1 $userId): array
    {
        $qb = $this->createQueryBuilder(self::ALIAS);
        $qb->join('c.users', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId)
        ;

        return $qb->getQuery()->getResult();
    }
}

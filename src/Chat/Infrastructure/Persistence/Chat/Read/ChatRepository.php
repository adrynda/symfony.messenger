<?php

declare(strict_types=1);

namespace App\Chat\Infrastructure\Persistence\Chat\Read;

use App\Chat\Domain\Repository\Chat\Read\ChatFinderInterface;
use App\Chat\Domain\Repository\Chat\Read\ChatRepositoryInterface;
use App\Chat\Domain\Repository\Chat\Read\UserChatRepositoryInterface;
use App\Chat\Domain\Model\Chat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\UuidV1;

final class ChatRepository extends ServiceEntityRepository
    implements ChatRepositoryInterface, UserChatRepositoryInterface, ChatFinderInterface
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
            ->setParameter('userId', $userId->toBinary())
        ;

        return $qb->getQuery()->getResult();
    }

    public function findByParticipants(array $usersIds): ?Chat
    {
        $qb = $this->createQueryBuilder(self::ALIAS);

        $qb
            ->join('c.users', 'u')
            ->groupBy('c.id')
            ->having(
                $qb->expr()->andX(
                    $qb->expr()->eq('COUNT(u.id)', ':count'),
                    $qb->expr()->eq(
                        'SUM(CASE WHEN u.id IN (:userIds) THEN 1 ELSE 0 END)',
                        ':count'
                    )
                )
            )
            ->setParameter('count', count($usersIds))
            ->setParameter('userIds', array_map(fn (UuidV1 $id) => $id->toBinary(), $usersIds))
            ->setMaxResults(1)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}

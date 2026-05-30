<?php

declare(strict_types=1);

namespace App\Chat\Infrastructure\Persistence\Chat\Read;

use App\Chat\Domain\Repository\Chat\Read\MessageRepositoryInterface;
use App\Chat\Domain\Model\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\UuidV1;

final class MessageRepository extends ServiceEntityRepository implements MessageRepositoryInterface
{
    private const ALIAS = 'm';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function lastLimitedFromChat(UuidV1 $chatId, int $limit): array
    {
        return $this->chatPagination($chatId, $limit);
    }

    public function chatPagination(UuidV1 $chatId, int $limit, int $page = 1): array
    {
        $page -= 1;
        $qb = $this->createQueryBuilder(self::ALIAS);

        $qb->select(self::ALIAS)
            ->join(self::ALIAS . '.chat', 'c')
            ->where($qb->expr()->eq('c.id', ':chatId'))
            ->setParameter('chatId', $chatId, 'uuid')
            ->orderBy(self::ALIAS . '.sentAt', 'DESC')
            ->setMaxResults($limit)
            ->setFirstResult(($page * $limit))
        ;

        return $qb->getQuery()->getResult();
    }
}

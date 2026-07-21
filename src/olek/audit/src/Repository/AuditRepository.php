<?php

namespace Olek\Audit\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Olek\Audit\Doctrine\EntityIdentifierResolver;
use Olek\Audit\Entity\Audit;
use Olek\Audit\Enum\SortDirection;

final class AuditRepository
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    /**
     * @return Audit[]
     */
    public function findHistoryFor(
        object $entity,
        ?int $limit = null,
        ?int $offset = null,
        SortDirection $order = SortDirection::Desc,
    ): array {
        return $this->entityManager->createQueryBuilder()
            ->select('audit')
            ->from(Audit::class, 'audit')
            ->andWhere('audit.entity.class = :entityClass')
            ->andWhere('audit.entity.id = :entityId')
            ->orderBy('audit.action.timestamp', $order->value)
            ->setParameter('entityClass', $entity::class)
            ->setParameter('entityId', EntityIdentifierResolver::resolve($this->entityManager, $entity))
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }
}

<?php

declare(strict_types=1);

namespace Olek\Audit\Tests\Integration;

use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;
use Olek\Audit\Doctrine\EntityIdentifierResolver;
use Olek\Audit\DTO\AuditPayload;
use Olek\Audit\Entity\Audit;
use Olek\Audit\Enum\AuditActionTypeEnum;
use Olek\Audit\Enum\SortDirection;
use Olek\Audit\Identifier\NativeAuditIdGenerator;
use Olek\Audit\Repository\AuditRepository;
use Olek\Audit\Tests\Integration\Fixture\AuditableArticle;
use Olek\Audit\Tests\Integration\Support\EntityManagerFactory;

final class AuditRepositoryTest extends Unit
{
    private EntityManagerInterface $em;

    private AuditRepository $repository;

    protected function _before(): void
    {
        $this->em = EntityManagerFactory::createInMemory();
        $this->repository = new AuditRepository($this->em);
    }

    public function testFindHistoryForFiltersByEntityClassAndId(): void
    {
        $article = $this->persistArticle();
        $otherArticle = $this->persistArticle();

        $this->persistAudit($article, new \DateTimeImmutable('2026-01-01'));
        $this->persistAudit($otherArticle, new \DateTimeImmutable('2026-01-02'));

        $history = $this->repository->findHistoryFor($article);

        $this->assertCount(1, $history);
        $this->assertSame(EntityIdentifierResolver::resolve($this->em, $article), $history[0]->entity->id);
    }

    public function testFindHistoryForOrdersDescendingByDefault(): void
    {
        $article = $this->persistArticle();

        $this->persistAudit($article, new \DateTimeImmutable('2026-01-01'));
        $this->persistAudit($article, new \DateTimeImmutable('2026-01-03'));
        $this->persistAudit($article, new \DateTimeImmutable('2026-01-02'));

        $history = $this->repository->findHistoryFor($article);

        $this->assertSame(['2026-01-03', '2026-01-02', '2026-01-01'], $this->dates($history));
    }

    public function testFindHistoryForCanSortAscending(): void
    {
        $article = $this->persistArticle();

        $this->persistAudit($article, new \DateTimeImmutable('2026-01-02'));
        $this->persistAudit($article, new \DateTimeImmutable('2026-01-01'));

        $history = $this->repository->findHistoryFor($article, order: SortDirection::Asc);

        $this->assertSame(['2026-01-01', '2026-01-02'], $this->dates($history));
    }

    public function testFindHistoryForWithoutLimitReturnsEntireHistory(): void
    {
        $article = $this->persistArticle();

        for ($day = 1; $day <= 5; $day++) {
            $this->persistAudit($article, new \DateTimeImmutable(sprintf('2026-01-%02d', $day)));
        }

        $this->assertCount(5, $this->repository->findHistoryFor($article));
    }

    public function testFindHistoryForSupportsLimitAndOffset(): void
    {
        $article = $this->persistArticle();

        for ($day = 1; $day <= 5; $day++) {
            $this->persistAudit($article, new \DateTimeImmutable(sprintf('2026-01-%02d', $day)));
        }

        // malejąco: 05,04,03,02,01 -> limit 2, offset 1 => 04,03
        $history = $this->repository->findHistoryFor($article, limit: 2, offset: 1);

        $this->assertSame(['2026-01-04', '2026-01-03'], $this->dates($history));
    }

    private function persistArticle(): AuditableArticle
    {
        $article = new AuditableArticle();
        $article->title = 'x';

        $this->em->persist($article);
        $this->em->flush();

        return $article;
    }

    private function persistAudit(object $entity, \DateTimeImmutable $timestamp): void
    {
        $payload = new AuditPayload(
            entityClass: $entity::class,
            entityId: EntityIdentifierResolver::resolve($this->em, $entity),
            actionType: AuditActionTypeEnum::Update,
            diff: [],
            timestamp: $timestamp,
        );

        $audit = Audit::create((new NativeAuditIdGenerator())->generate(), $payload);

        $this->em->persist($audit);
        $this->em->flush();
    }

    /**
     * @param Audit[] $history
     * @return string[]
     */
    private function dates(array $history): array
    {
        return array_map(
            static fn (Audit $audit): string => $audit->action->timestamp->format('Y-m-d'),
            $history,
        );
    }
}

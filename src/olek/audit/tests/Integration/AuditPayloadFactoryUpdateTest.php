<?php

declare(strict_types=1);

namespace Olek\Audit\Tests\Integration;

use Olek\Audit\DTO\AuditPayload;
use Olek\Audit\DTO\EntityPropertyDiff;
use Olek\Audit\Enum\AuditActionTypeEnum;
use Olek\Audit\Tests\Integration\Fixture\AuditableArticle;
use Olek\Audit\Tests\Integration\Fixture\AuditableCategory;
use Olek\Audit\Tests\Integration\Fixture\AuditableTag;
use Olek\Audit\Tests\Integration\Fixture\PlainEntity;
use Olek\Audit\Tests\Integration\Support\AuditIntegrationTestCase;

final class AuditPayloadFactoryUpdateTest extends AuditIntegrationTestCase
{
    public function testInitialPersistDoesNotDispatchAnAudit(): void
    {
        $article = new AuditableArticle();
        $article->title = 'Original';

        $this->em->persist($article);
        $this->em->flush();

        $this->assertSame([], $this->dispatcher->dispatched);
    }

    public function testChangedScalarFieldAppearsInDiffUnchangedFieldsDoNot(): void
    {
        $article = $this->persistedArticle(title: 'Original');

        $article->title = 'Changed';
        $this->em->flush();

        $this->assertCount(1, $this->dispatcher->dispatched);

        $payload = $this->dispatcher->dispatched[0];
        $this->assertSame(AuditActionTypeEnum::Update, $payload->actionType);
        $this->assertSame((string) $article->id, $payload->entityId);
        $this->assertSame(AuditableArticle::class, $payload->entityClass);

        $diff = $this->diffByField($payload);
        $this->assertSame(['Original', 'Changed'], [$diff['title']->oldValue, $diff['title']->newValue]);
    }

    public function testIgnoredPropertyIsExcludedFromDiff(): void
    {
        $article = $this->persistedArticle(title: 'Original', internalNote: 'secret-1');

        $article->title = 'Changed';
        $article->internalNote = 'secret-2';
        $this->em->flush();

        $diff = $this->diffByField($this->dispatcher->dispatched[0]);
        $this->assertArrayHasKey('title', $diff);
        $this->assertArrayNotHasKey('internalNote', $diff);
    }

    public function testToOneRelationDiffUsesResolvedIdNotObject(): void
    {
        $categoryA = new AuditableCategory('A');
        $categoryB = new AuditableCategory('B');
        $this->em->persist($categoryA);
        $this->em->persist($categoryB);
        $this->em->flush();

        $article = $this->persistedArticle(title: 'Original');

        $article->category = $categoryA;
        $this->em->flush();
        $this->dispatcher->dispatched = [];

        $article->category = $categoryB;
        $this->em->flush();

        $diff = $this->diffByField($this->dispatcher->dispatched[0]);
        $this->assertIsString($diff['category']->oldValue);
        $this->assertSame((string) $categoryA->id, $diff['category']->oldValue);
        $this->assertSame((string) $categoryB->id, $diff['category']->newValue);
    }

    public function testToManyRelationDiffListsIdsBeforeAndAfter(): void
    {
        $tag1 = new AuditableTag('one');
        $tag2 = new AuditableTag('two');
        $this->em->persist($tag1);
        $this->em->persist($tag2);
        $this->em->flush();

        $article = $this->persistedArticle(title: 'Original');

        $article->tags->add($tag1);
        $this->em->flush();
        $this->dispatcher->dispatched = [];

        $article->tags->removeElement($tag1);
        $article->tags->add($tag2);
        $this->em->flush();

        $diff = $this->diffByField($this->dispatcher->dispatched[0]);
        $this->assertSame([(string) $tag1->id], $diff['tags']->oldValue);
        $this->assertSame([(string) $tag2->id], $diff['tags']->newValue);
    }

    public function testNonAuditableEntityIsNeverDispatched(): void
    {
        $entity = new PlainEntity();
        $entity->value = 'a';
        $this->em->persist($entity);
        $this->em->flush();

        $entity->value = 'b';
        $this->em->flush();

        $this->assertSame([], $this->dispatcher->dispatched);
    }

    private function persistedArticle(string $title, ?string $internalNote = null): AuditableArticle
    {
        $article = new AuditableArticle();
        $article->title = $title;
        $article->internalNote = $internalNote;

        $this->em->persist($article);
        $this->em->flush();
        $this->dispatcher->dispatched = [];

        return $article;
    }

    /**
     * @return array<string, EntityPropertyDiff>
     */
    private function diffByField(AuditPayload $payload): array
    {
        $result = [];
        foreach ($payload->diff as $entry) {
            $result[$entry->name] = $entry;
        }

        return $result;
    }
}

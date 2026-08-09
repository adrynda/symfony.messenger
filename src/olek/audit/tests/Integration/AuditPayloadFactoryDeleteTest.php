<?php

declare(strict_types=1);

namespace Olek\Audit\Tests\Integration;

use Olek\Audit\DTO\AuditPayload;
use Olek\Audit\DTO\EntityPropertyDiff;
use Olek\Audit\Enum\AuditActionTypeEnum;
use Olek\Audit\Tests\Integration\Fixture\AuditableArticle;
use Olek\Audit\Tests\Integration\Fixture\AuditableCategory;
use Olek\Audit\Tests\Integration\Fixture\AuditableTag;
use Olek\Audit\Tests\Integration\Support\AuditIntegrationTestCase;

final class AuditPayloadFactoryDeleteTest extends AuditIntegrationTestCase
{
    public function testDeleteDumpsAllFieldsAndRelationsAsOldValueNullPairs(): void
    {
        $category = new AuditableCategory('A');
        $tag = new AuditableTag('one');
        $this->em->persist($category);
        $this->em->persist($tag);
        $this->em->flush();

        $article = new AuditableArticle();
        $article->title = 'Original';
        $article->internalNote = 'secret';
        $article->category = $category;
        $article->tags->add($tag);

        $this->em->persist($article);
        $this->em->flush();
        $this->dispatcher->dispatched = [];

        $articleId = $article->id;

        $this->em->remove($article);
        $this->em->flush();

        $this->assertCount(1, $this->dispatcher->dispatched);

        $payload = $this->dispatcher->dispatched[0];
        $this->assertSame(AuditActionTypeEnum::Delete, $payload->actionType);
        $this->assertSame((string) $articleId, $payload->entityId);
        $this->assertSame(AuditableArticle::class, $payload->entityClass);

        $diff = $this->diffByField($payload);

        $this->assertSame(['Original', null], [$diff['title']->oldValue, $diff['title']->newValue]);
        $this->assertArrayNotHasKey('internalNote', $diff, '#[Ignore] powinno wykluczyć pole z dumpu przy delete');
        $this->assertSame(
            [(string) $category->id, null],
            [$diff['category']->oldValue, $diff['category']->newValue],
        );
        $this->assertSame(
            [[(string) $tag->id], []],
            [$diff['tags']->oldValue, $diff['tags']->newValue],
        );
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

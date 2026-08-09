<?php

declare(strict_types=1);

namespace Olek\Audit\Tests\Integration\Fixture;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Olek\Audit\Attribute\Auditable;
use Olek\Audit\Attribute\Ignore;

#[ORM\Entity]
#[Auditable]
class AuditableArticle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    public ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    public string $title = '';

    #[Ignore]
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    public ?string $internalNote = null;

    #[ORM\ManyToOne(targetEntity: AuditableCategory::class)]
    public ?AuditableCategory $category = null;

    /** @var Collection<int, AuditableTag> */
    #[ORM\ManyToMany(targetEntity: AuditableTag::class)]
    public Collection $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }
}

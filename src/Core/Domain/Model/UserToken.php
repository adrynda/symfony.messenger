<?php

declare(strict_types=1);

namespace App\Core\Domain\Model;

use App\Core\Domain\Enum\UserTokenTypeEnum;
use App\Core\Domain\Model\User\User;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV1;

#[ORM\Entity]
#[ORM\Table(name: 'user_tokens')]
class UserToken extends AbstractUuidEntity
{
    private function __construct(
        UuidV1 $id,

        #[ORM\Column(enumType: UserTokenTypeEnum::class)]
        public readonly UserTokenTypeEnum $enum,

        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'userTokens')]
        #[ORM\JoinColumn(nullable: false)]
        public readonly User $user,

        #[ORM\Column(type: 'datetime_immutable')]
        public readonly ?DateTimeImmutable $expiresAt,
    ) {
        parent::__construct($id);
    }

    public static function create(
        UserTokenTypeEnum $enum,
        User $user,
        ?DateTimeImmutable $expiresAt = null,
    ): self {
        return new self(
            id: Uuid::v1(),
            enum: $enum,
            user: $user,
            expiresAt: $expiresAt,
        );
    }
}

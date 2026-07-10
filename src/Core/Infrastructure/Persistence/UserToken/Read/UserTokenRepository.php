<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Persistence\UserToken\Read;

use App\Core\Domain\Model\UserToken;
use App\Core\Domain\Repository\Read\UserTokenRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class UserTokenRepository extends ServiceEntityRepository implements UserTokenRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserToken::class);
    }
}

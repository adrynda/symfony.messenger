<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Persistence\User\Read;

use App\Core\Domain\WriteModel\User\User;
use App\Core\Domain\Repository\Read\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
}

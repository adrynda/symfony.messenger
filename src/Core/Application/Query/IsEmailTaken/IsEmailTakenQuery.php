<?php

declare(strict_types=1);

namespace App\Core\Application\Query\IsEmailTaken;

final readonly class IsEmailTakenQuery
{
    public function __construct(
        public string $email,
    ) {}
}

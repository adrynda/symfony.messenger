<?php

namespace App\Core\UserInterface\DTO;

final readonly class TurboStreamDTO
{
    public function __construct(
        public string $action,
        public string $target,
        public string $template,
    ) {}
}

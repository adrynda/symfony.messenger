<?php

namespace App\Core\UserInterface\DTO;

final readonly class TurboFrameDTO
{
    public function __construct(
        public string $id,
        public string $template,
    ) {}
}

<?php

declare(strict_types=1);

namespace App\Chat\Domain\Service;

use App\Chat\Domain\DTO\PublishDTO;

interface PublisherInterface
{
    public function publish(PublishDTO $dto): string;
}

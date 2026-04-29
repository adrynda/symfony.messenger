<?php

declare(strict_types=1);

namespace App\Mercure\Chat\Domain\Service;

use App\Mercure\Chat\Domain\DTO\PublishDTO;

interface PublisherInterface
{
    public function publish(PublishDTO $dto): string;
}

<?php

declare(strict_types=1);

namespace App\Chat\Mercure\Domain\Service;

use App\Chat\Mercure\Domain\DTO\PublishDTO;

interface PublisherInterface
{
    public function publish(PublishDTO $dto): string;
}

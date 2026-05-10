<?php

declare(strict_types=1);

namespace App\Messenger\Mercure\Chat\Infrastructure\Service;

use App\Messenger\Mercure\Chat\Domain\DTO\PublishDTO;
use App\Messenger\Mercure\Chat\Domain\Service\PublisherInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

final readonly class Publisher implements PublisherInterface
{
    public function __construct(
        private HubInterface $hub,
    ) {
    }

    public function publish(PublishDTO $dto): string
    {
        $update = new Update(
            $dto->topic,
            json_encode($dto->data),
        );

        return $this->hub->publish($update);
    }
}

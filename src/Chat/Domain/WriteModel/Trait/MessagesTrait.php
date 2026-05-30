<?php

declare(strict_types=1);

namespace App\Chat\Domain\WriteModel\Trait;

use App\Chat\Domain\WriteModel\Message;
use Doctrine\Common\Collections\Collection;

trait MessagesTrait
{
    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
        }

        return $this;
    }

    public function getMessages(): Collection
    {
        return $this->messages;
    }
}

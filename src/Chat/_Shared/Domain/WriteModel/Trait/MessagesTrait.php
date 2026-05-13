<?php

declare(strict_types=1);

namespace App\Chat\_Shared\Domain\WriteModel\Trait;

use App\Chat\_Shared\Domain\WriteModel\Message;
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

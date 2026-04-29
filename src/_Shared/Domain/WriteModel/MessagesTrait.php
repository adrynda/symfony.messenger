<?php

declare(strict_types=1);

namespace App\_Shared\Domain\WriteModel;

trait MessagesTrait
{
    public function addMessage(Message $message): self
    {
        $isFound = current(array_filter($this->messages, fn (Message $msg) => $msg->id === $message->id));

        if (!$isFound) {
            $this->messages[] = $message;
        }

        return $this;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }
}

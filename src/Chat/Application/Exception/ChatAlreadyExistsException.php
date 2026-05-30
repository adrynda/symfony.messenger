<?php

namespace App\Chat\Application\Exception;

use App\Chat\Domain\Model\Chat;
use RuntimeException;

class ChatAlreadyExistsException extends RuntimeException
{
    public function __construct(
        public readonly Chat $chat,
        string $message = 'exception.chat.already_exists',
        int $code = 0,
        \Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}

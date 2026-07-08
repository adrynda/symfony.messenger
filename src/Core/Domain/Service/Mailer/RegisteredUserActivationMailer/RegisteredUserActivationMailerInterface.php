<?php

namespace App\Core\Domain\Service\Mailer\RegisteredUserActivationMailer;

interface RegisteredUserActivationMailerInterface
{
    public function send(RegisteredUserActivationMailerDTO $dto): void;
}

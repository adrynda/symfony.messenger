<?php

declare(strict_types=1);

namespace Olek\Audit\Actor;

interface ActorResolverInterface
{
    /**
     * Identyfikator wykonawcy zmiany (login, e-mail, nazwa joba/workera) albo null, gdy nie da się go ustalić.
     */
    public function resolve(): ?string;
}

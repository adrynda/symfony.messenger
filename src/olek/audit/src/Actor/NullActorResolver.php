<?php

declare(strict_types=1);

namespace Olek\Audit\Actor;

/**
 * Domyślny resolver dla hostów bez naturalnego pojęcia "aktora" (np. Bridge\NativePhp)
 * albo jako placeholder w Bridge\Symfony do nadpisania przez appkę.
 */
final class NullActorResolver implements ActorResolverInterface
{
    public function resolve(): ?string
    {
        return null;
    }
}

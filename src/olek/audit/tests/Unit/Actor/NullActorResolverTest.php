<?php

declare(strict_types=1);

namespace Olek\Audit\Tests\Unit\Actor;

use Codeception\Test\Unit;
use Olek\Audit\Actor\NullActorResolver;

final class NullActorResolverTest extends Unit
{
    public function testResolveReturnsNull(): void
    {
        $this->assertNull((new NullActorResolver())->resolve());
    }
}

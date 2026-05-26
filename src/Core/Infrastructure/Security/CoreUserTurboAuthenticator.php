<?php

namespace App\Core\Infrastructure\Security;

use Symfony\Component\HttpFoundation\Request;

class CoreUserTurboAuthenticator extends AbstractAuthenticator
{
    protected function getCsrfToken(Request $request): string
    {
        return $request->headers->get('csrf-token');
    }
}

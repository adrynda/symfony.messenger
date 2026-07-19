<?php

declare(strict_types=1);

namespace App\Core\UserInterface\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/panel/logout', name: 'core_logout', methods: ['GET'])]
final class LogoutController extends AbstractCoreController
{
    public function __invoke(): Response
    {
        throw new \LogicException('security.handled_by');
    }
}

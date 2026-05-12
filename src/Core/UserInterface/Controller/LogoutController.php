<?php

declare(strict_types=1);

namespace App\Core\UserInterface\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/core', name: 'core')]
final class LogoutController extends AbstractController
{
    #[Route('/logout', name: '_logout', methods: ['GET'])]
    public function logout(): Response
    {
        throw new \LogicException('Handled by security.');
    }
}

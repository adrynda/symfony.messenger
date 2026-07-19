<?php

declare(strict_types=1);

namespace App\Core\UserInterface\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/panel', name: 'core')]
final class HomeController extends AbstractCoreController
{
    #[Route('/home', name: '_home_view', methods: ['GET'])]
    public function home(): Response
    {
        return $this->render('home/main.html.twig');
    }
}

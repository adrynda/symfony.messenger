<?php

declare(strict_types=1);

namespace App\Core\UserInterface\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/core', name: 'core')]
final class HomeController extends AbstractController
{
    #[Route('/home', name: '_home_view', methods: ['GET'])]
    public function home(): Response
    {
        return $this->render('core/home.html.twig');
    }
}

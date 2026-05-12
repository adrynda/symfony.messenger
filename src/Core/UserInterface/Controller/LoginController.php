<?php

declare(strict_types=1);

namespace App\Core\UserInterface\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/core', name: 'core')]
final class LoginController extends AbstractController
{
    #[Route('/login', name: '_login_view', methods: ['GET'])]
    public function view(): Response
    {
        if (!empty($this->getUser())) {
            return $this->redirectToRoute('core_home_view');
        }

        return $this->render('core/login.html.twig');
    }

    #[Route('/login', name: '_login_login', methods: ['POST'])]
    public function login(): Response
    {
        throw new \LogicException('Handled by security.');
    }
}

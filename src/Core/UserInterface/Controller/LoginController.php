<?php

declare(strict_types=1);

namespace App\Core\UserInterface\Controller;

use App\Core\UserInterface\Form\LoginType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/core/login', name: 'core_login')]
final class LoginController extends AbstractCoreController
{
    #[Route(name: '', methods: ['GET', 'POST'])]
    public function login(): Response
    {
        if (!empty($this->getUser())) {
            return $this->redirectToRoute('core_home_view');
        }

        $form = $this->createForm(LoginType::class);
        $form->handleRequest($this->request);

        return $this->render('core/login.html.twig', ['form' => $form]);
    }
}

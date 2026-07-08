<?php

declare(strict_types=1);

namespace App\Core\UserInterface\Controller;

use App\Core\UserInterface\Form\LoginType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/panel/login', name: 'core_login', methods: ['GET', 'POST'])]
final class LoginController extends AbstractCoreController
{
    public function __invoke(AuthenticationUtils $authenticationUtils): Response
    {
        if (!empty($this->getUser())) {
            return $this->redirectToRoute('core_home_view');
        }

        $form = $this->createForm(LoginType::class);
        $form->handleRequest($this->request);

        return $this->render('login/main.html.twig', [
            'form' => $form,
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Core\UserInterface\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RegistrationController extends AbstractController
{
    #[Route('/core/registration', name: 'core_registration', methods: ['GET'])]
    public function __invoke(): Response
    {
        if (!empty($this->getUser())) {
            return $this->redirectToRoute('core_home_view');
        }

        return $this->render('core/registration.html.twig');
    }
}

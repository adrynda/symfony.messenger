<?php

declare(strict_types=1);

namespace App\Core\UserInterface\Controller;

use App\Core\Domain\Model\UserToken;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/panel/activation/{userTokenId}', name: 'core_activation', methods: ['GET'])]
final class ActivationController extends AbstractController
{
    public function __invoke(UserToken $userToken): Response
    {
        if (!empty($this->getUser())) {
            return $this->redirectToRoute('core_home_view');
        }

        return $this->render('registration/main.html.twig');
    }
}

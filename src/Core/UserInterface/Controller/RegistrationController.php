<?php

declare(strict_types=1);

namespace App\Core\UserInterface\Controller;

use App\Core\Application\Command\RegisterUser\RegisterUserCommand;
use App\Core\Domain\DTO\RegistrationDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/core', name: 'core')]
final class RegistrationController extends AbstractController
{
    #[Route('/registration', name: '_registration_view', methods: ['GET'])]
    public function view(): Response
    {
        return $this->render('core/registration.html.twig');
    }
    #[Route('/registration', name: '_registration_post', methods: ['POST'])]
    public function register(
        #[MapRequestPayload]
        RegistrationDTO $registrationDTO,
//        ValidatorInterface $validator,
        #[Autowire(service: 'core.registration.command.bus')]
        MessageBusInterface $commandBus,
    ): Response {
        try {
            if (!$this->isCsrfTokenValid('register_token', $registrationDTO->_csrfToken)) {
                throw new BadRequestHttpException('Invalid CSRF Token.');
            }
            $commandBus->dispatch(new RegisterUserCommand($registrationDTO));
            $this->addFlash('core.registration.register.success', 'Activation mail has been sent on your inbox.');
            return $this->redirectToRoute('core_login_view');
        } catch (\Throwable $exception) {
            $this->addFlash('core.registration.register.error', $exception->getMessage());
            return $this->redirectToRoute('core_registration_view');
        }
    }
}

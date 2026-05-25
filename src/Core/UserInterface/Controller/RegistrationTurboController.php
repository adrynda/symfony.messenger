<?php

declare(strict_types=1);

namespace App\Core\UserInterface\Controller;

use App\Core\Application\Command\RegisterUser\RegisterUserCommand;
use App\Core\Domain\DTO\RegistrationDTO;
use App\Core\UserInterface\Form\RegistrationType;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/core/registration', name: 'core_registration')]
final class RegistrationTurboController extends AbstractTurboController
{
    #[Route(name: '', methods: ['GET', 'POST'])]
    public function registration(
        #[Autowire(service: 'core.registration.command.bus')]
        MessageBusInterface $commandBus,
    ): Response {
        if (!empty($this->getUser())) {
            return $this->redirectToRoute('core_home_view');
        }

        $form = $this->createForm(RegistrationType::class, new RegistrationDTO);
        $form->handleRequest($this->request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $commandBus->dispatch(new RegisterUserCommand($form->getData()));
                $this->addFlash('core.registration.form.success', 'core.registration.form.success.notify.email');
                return $this->turboRedirect('core_login');
            }

            if ($this->isTurboStreamFormat()) {
                return $this->renderTurboStream('core/registration_turbo.html.twig', ['form' => $form]);
            }
        }

        return $this->render('core/registration.html.twig', ['form' => $form]);
    }
}

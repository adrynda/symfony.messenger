<?php

declare(strict_types=1);

namespace App\Core\UserInterface\Controller;

use App\Core\Application\Command\RegisterUser\RegisterUserCommand;
use App\Core\Domain\DTO\RegistrationDTO;
use App\Core\UserInterface\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/core/registration', name: 'core_registration')]
final class RegistrationController extends AbstractController
{
    #[Route(name: '', methods: ['GET', 'POST'])]
    public function registration(
        Request $request,
        #[Autowire(service: 'core.registration.command.bus')]
        MessageBusInterface $commandBus,
    ): Response {
        if (!empty($this->getUser())) {
            return $this->redirectToRoute('core_home_view');
        }

        $form = $this->createForm(RegistrationFormType::class, new RegistrationDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandBus->dispatch(new RegisterUserCommand($form->getData()));
            $this->addFlash('core.registration.form.success', 'core.registration.form.success.notify.email');
            return $this->redirectToRoute('core_login_view');
        }

        return $this->render('core/registration.html.twig', ['form' => $form]);
    }
}

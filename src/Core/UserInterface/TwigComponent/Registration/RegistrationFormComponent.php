<?php

namespace App\Core\UserInterface\TwigComponent\Registration;

use App\Core\Application\Command\RegisterUser\RegisterUserCommand;
use App\Core\Domain\DTO\RegistrationDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('registrationForm', template: 'core/components/_form.html.twig')]
class RegistrationFormComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    public function __construct(
        #[Autowire(service: 'core.registration.command.bus')]
        private readonly MessageBusInterface $commandBus,
    ) {}

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(RegistrationComponentType::class, new RegistrationDTO);
    }

    #[LiveAction]
    public function register(): Response
    {
        $this->submitForm();

        /** @var RegistrationDTO $dto */
        $dto = $this->getForm()->getData();

        $this->commandBus->dispatch(new RegisterUserCommand($dto));

        $this->addFlash('core.registration.form.success', 'core.registration.form.success.notify.email');

        return $this->redirectToRoute('core_login');
    }
}

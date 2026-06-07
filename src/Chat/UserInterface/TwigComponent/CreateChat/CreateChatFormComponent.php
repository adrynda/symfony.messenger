<?php

namespace App\Chat\UserInterface\TwigComponent\CreateChat;

use App\Chat\Application\Exception\ChatAlreadyExistsException;
use App\Chat\Application\Service\CreateChatDTO;
use App\Chat\Application\Service\CreateChatService;
use App\Chat\Domain\Enum\ChatRoleEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('createChatForm', template: '@Chat/components/_form.html.twig')]
#[IsGranted(ChatRoleEnum::RoleCreateChat->value)]
class CreateChatFormComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    public function __construct(
        #[Autowire(service: 'chat.command.bus')]
        private readonly MessageBusInterface $commandBus,
        private readonly CreateChatService $createChatService,
    ) {}

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(CreateChatComponentType::class, new CreateChatDTO(), [
            'current_user_id' => $this->getUser()->id,
        ]);
    }

    #[LiveAction]
    public function submit(): Response
    {
        $this->submitForm();

        /** @var CreateChatDTO $dto */
        $dto = $this->getForm()->getData();

        try {
            $chat = $this->createChatService->createChat($dto, $this->getUser());
        } catch (ChatAlreadyExistsException $e) {
            return $this->redirectToRoute('chat_mercure_chat_view', ['id' => $e->chat->id]);
        }

        $this->addFlash('chat.create.form.success', 'chat.create.form.success.notify');

        return $this->redirectToRoute('chat_mercure_chat_view', ['id' => $chat->id]);
    }
}

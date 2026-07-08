<?php

namespace App\Core\Infrastructure\Mailer;

use App\Core\Domain\Service\Mailer\RegisteredUserActivationMailer\RegisteredUserActivationMailerDTO;
use App\Core\Domain\Service\Mailer\RegisteredUserActivationMailer\RegisteredUserActivationMailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\BodyRendererInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final readonly class RegisteredUserActivationMailer implements RegisteredUserActivationMailerInterface
{
    public function __construct(
        private Address $from,
        private MailerInterface $mailer,
        private BodyRendererInterface $bodyRenderer,
        private TranslatorInterface $translator,
    ) {
    }

    public function send(RegisteredUserActivationMailerDTO $dto): void
    {
        $email = (new TemplatedEmail())
            ->from($this->from)
            ->to(new Address($dto->email))
            ->subject($this->translator->trans('registration.email.user_activation.subject', ['%username%' => $dto->username]))
            ->htmlTemplate('registration/email.html.twig') // todo: dopiero do wygenerowania
            ->textTemplate('registration/email.txt.twig') // todo: dopiero do wygenerowania
            ->context([
                'username' => $dto->username,
                'activationLink' => $dto->activationLink,
            ])
        ;

        if (null !== $dto->locale) {
            $email->locale($dto->locale);
        }

        $this->bodyRenderer->render($email);

        $this->mailer->send($email);
    }
}

<?php

namespace App\Core\UserInterface\Form;

use App\Core\Domain\DTO\RegistrationDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationType extends AbstractType
{
    protected const TRANS_KEY = 'registration.form.field.';

    public function __construct(
        protected readonly TranslatorInterface $translator
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => self::TRANS_KEY . 'username.label',
                'required' => true,
                'attr' => [
                    'placeholder' => self::TRANS_KEY . 'username.placeholder',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => self::TRANS_KEY . 'email.label',
                'required' => true,
                'attr' => [
                    'placeholder' => self::TRANS_KEY . 'email.placeholder',
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => $this->translator->trans(self::TRANS_KEY . 'password.mismatch'),
                'required' => true,
                'options' => [
                    'always_empty' => false,
                ],
                'first_options' => [
                    'label' => self::TRANS_KEY . 'password_main.label',
                ],
                'second_options' => [
                    'label' => self::TRANS_KEY . 'password_repeat.label',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RegistrationDTO::class,
            'csrf_protection' => true,
        ]);
    }
}

<?php

namespace App\Core\UserInterface\Form;

use App\Core\Domain\DTO\RegistrationDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    private const TRANS_KEY = 'core.form.registration.';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => self::TRANS_KEY . 'username',
                'required' => true,
                'attr' => [
                    'placeholder' => self::TRANS_KEY . 'placeholder.username',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => self::TRANS_KEY . 'email',
                'required' => true,
                'attr' => [
                    'placeholder' => self::TRANS_KEY . 'placeholder.email',
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => self::TRANS_KEY . 'password.mismatch',
                'required' => true,
                'first_options' => [
                    'label' => self::TRANS_KEY . 'password',
                ],
                'second_options' => [
                    'label' => self::TRANS_KEY . 'password.repeat',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => self::TRANS_KEY . 'submit',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RegistrationDTO::class,
            'csrf_protection' => true,
            'translation_domain' => 'core',
        ]);
    }
}

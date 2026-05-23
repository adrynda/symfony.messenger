<?php

namespace App\Core\UserInterface\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginFormType extends AbstractType
{
    private const TRANS_KEY = 'core.form.login.';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => self::TRANS_KEY . 'email',
                'attr' => [
                    'placeholder' => self::TRANS_KEY . 'placeholder.email',
                ],
                'required' => true,
            ])
            ->add('password', PasswordType::class, [
                'required' => true,
                'label' => self::TRANS_KEY . 'password',
            ])
            ->add('submit', SubmitType::class, [
                'label' => self::TRANS_KEY . 'submit',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'csrf_token_id' => 'authenticate',
            'translation_domain' => 'core',
        ]);
    }
}

<?php

namespace App\Core\UserInterface\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractType
{
    private const TRANS_KEY = 'login.form.field.';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => self::TRANS_KEY . 'email.label',
                'attr' => [
                    'placeholder' => self::TRANS_KEY . 'email.placeholder',
                ],
                'required' => true,
            ])
            ->add('password', PasswordType::class, [
                'required' => true,
                'label' => self::TRANS_KEY . 'password.label',
            ])
            ->add('submit', SubmitType::class, [
                'label' => self::TRANS_KEY . 'submit.label',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'csrf_token_id' => 'authenticate',
        ]);
    }
}

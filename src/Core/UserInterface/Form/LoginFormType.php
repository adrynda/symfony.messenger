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
    private const INPUT_STYLES = 'w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500';
    private const LABEL_STYLES = 'block text-sm font-medium text-gray-700 mb-1';
    private const ROW_STYLES = 'mb-4';
    private const TRANS_KEY = 'core.form.login.';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => self::TRANS_KEY . 'email',
                'required' => true,
                'attr' => [
                    'class' => self::INPUT_STYLES,
                    'placeholder' => self::TRANS_KEY . 'email.placeholder',
                ],
                'label_attr' => ['class' => self::LABEL_STYLES],
                'row_attr'   => ['class' => self::ROW_STYLES],
            ])
            ->add('password', PasswordType::class, [
                'required' => true,
                'label' => self::TRANS_KEY . 'password',
                'attr' => [
                    'class' => self::INPUT_STYLES,
                    'placeholder' => self::TRANS_KEY . 'password.placeholder',
                ],
                'label_attr' => ['class' => self::LABEL_STYLES],
                'row_attr'   => ['class' => self::ROW_STYLES],
            ])
            ->add('submit', SubmitType::class, [
                'label' => self::TRANS_KEY . 'submit',
                'attr' => [
                    'class' => 'w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200',
                ],
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

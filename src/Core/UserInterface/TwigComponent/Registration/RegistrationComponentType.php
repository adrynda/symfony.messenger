<?php

namespace App\Core\UserInterface\TwigComponent\Registration;

use App\Core\UserInterface\Form\RegistrationType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationComponentType extends RegistrationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('submit', SubmitType::class, [
                'label' => self::TRANS_KEY . 'submit',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'attr' => [
                'data-action' => 'live#action:prevent',
                'data-live-action-param' => 'register',
            ],
        ]);
    }
}

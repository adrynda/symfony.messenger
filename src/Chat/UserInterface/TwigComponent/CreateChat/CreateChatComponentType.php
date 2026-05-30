<?php

namespace App\Chat\UserInterface\TwigComponent\CreateChat;

use App\Chat\Application\Service\CreateChatDTO;
use App\Chat\UserInterface\Form\CreateChatType;
use App\Core\Domain\WriteModel\User\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateChatComponentType extends CreateChatType
{
    protected const TRANS_KEY = 'chat.form.';

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
                'data-live-action-param' => 'submit',
            ],
        ]);
    }
}

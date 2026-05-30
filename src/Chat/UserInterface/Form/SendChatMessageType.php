<?php

namespace App\Chat\UserInterface\Form;

use App\Chat\Application\Service\CreateChatDTO;
use App\Core\Domain\WriteModel\User\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SendChatMessageType extends AbstractType
{
    private const TRANS_KEY = 'chat.message.form.';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('users', TextType::class, [
//                'label' => self::TRANS_KEY . 'users',
                'attr' => [
                    'label' => self::TRANS_KEY . 'content'
                ],
                'required' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => self::TRANS_KEY . 'submit',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
//            'data_class' => CreateChatDTO::class,
            'csrf_protection' => true,
//            'csrf_token_id' => 'authenticate',
            'current_user_id' => null,
            'translation_domain' => 'chat',
        ]);
    }
}

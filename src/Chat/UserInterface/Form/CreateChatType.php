<?php

namespace App\Chat\UserInterface\Form;

use App\Chat\Application\Service\CreateChatDTO;
use App\Core\Domain\WriteModel\User\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateChatType extends AbstractType
{
    private const TRANS_KEY = 'chat.form.';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('users', EntityType::class, [
                'query_builder' => function (EntityRepository $er) use ($options) {
                    $qb = $er->createQueryBuilder('u');
                    return $qb
                        ->where($qb->expr()->neq('u.id', ':currentUserId'))
                        ->setParameter('currentUserId', $options['current_user_id'], UuidType::NAME)
                    ;
                },
                'class' => User::class,
                'multiple' => true,
                'label' => self::TRANS_KEY . 'users',
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
            'data_class' => CreateChatDTO::class,
            'csrf_protection' => true,
            'current_user_id' => null,
            'translation_domain' => 'chat',
        ]);
    }
}

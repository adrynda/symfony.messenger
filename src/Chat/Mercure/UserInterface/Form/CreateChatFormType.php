<?php

namespace App\Chat\Mercure\UserInterface\Form;

use App\Chat\Mercure\Application\Service\CreateChatDTO;
use App\Core\Domain\WriteModel\User\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateChatFormType extends AbstractType
{
    private const INPUT_STYLES = 'w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500';
    private const LABEL_STYLES = 'block text-sm font-medium text-gray-700 mb-1';
    private const ROW_STYLES = 'mb-4';
    private const TRANS_KEY = 'chat.form.chat.create.';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('users', EntityType::class, [
                'query_builder' => function (EntityRepository $er) use ($options) {
                    $qb = $er->createQueryBuilder('u');
                    return $qb
                        ->where($qb->expr()->neq('u.id', ':currentUserId'))
                        ->setParameter('currentUserId', $options['current_user_id'])
                    ;
                },
                'class' => User::class,
                'multiple' => true,
//                'expanded' => true,
                'label' => self::TRANS_KEY . 'users',
                'required' => true,
                'attr' => ['class' => 'space-y-2'],
                'choice_attr' => fn() => ['class' => 'peer'],
                'label_attr' => ['class' => 'flex items-center gap-3 rounded-xl border border-gray-200 px-4 py-3 hover:bg-gray-50 cursor-pointer peer-checked:border-blue-500 transition-colors'],
//                'attr' => ,
//                'label_attr' => ['class' => self::LABEL_STYLES],
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
            'data_class' => CreateChatDTO::class,
            'csrf_protection' => true,
            'csrf_token_id' => 'authenticate',
            'current_user_id' => null,
        ]);
    }
}

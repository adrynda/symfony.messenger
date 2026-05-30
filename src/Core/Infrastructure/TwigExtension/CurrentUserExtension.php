<?php

namespace App\Core\Infrastructure\TwigExtension;

use App\Chat\Domain\ReadModel\UserView;
use App\Core\Domain\WriteModel\User\User;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CurrentUserExtension extends AbstractExtension
{
    public function __construct(
        private readonly Security $security,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('current_user_view', $this->getCurrentUserView(...)),
        ];
    }

    public function getCurrentUserView(): ?UserView
    {
        /** @var ?User $user */
        $user = $this->security->getUser();
        return null === $user ? null : UserView::fromEntity($user);
    }
}

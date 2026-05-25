<?php

declare(strict_types=1);

namespace App\Core\UserInterface\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;


abstract class AbstractCoreController extends AbstractController
{
    protected readonly Request $request;
    public function __construct(
        RequestStack $requestStack,
    ) {
        $this->request = $requestStack->getCurrentRequest();
    }
}

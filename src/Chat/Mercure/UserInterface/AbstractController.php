<?php

declare(strict_types=1);

namespace App\Chat\Mercure\UserInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

abstract class AbstractController extends SymfonyAbstractController
{
    protected readonly Request $request;
    public function __construct(
        RequestStack $requestStack,
    ) {
        $this->request = $requestStack->getCurrentRequest();
    }

    protected function validCsrfToken(): void
    {
        if (!$this->isCsrfTokenValid($this->getCsrfTokenKey(), $this->request->request->get('_csrf_token'))) {
            throw new BadRequestHttpException('Invalid CSRF Token.');
        }
    }

    protected function getCsrfTokenKey(): string
    {
        throw new BadRequestHttpException('No CSRF Token key found.');
    }
}

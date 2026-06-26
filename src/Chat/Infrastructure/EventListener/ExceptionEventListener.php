<?php

declare(strict_types=1);

namespace App\Chat\Infrastructure\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Twig\Environment;

#[AsEventListener(event: KernelEvents::EXCEPTION, priority: 10)]
final readonly class ExceptionEventListener
{
    public function __construct(
        private Environment $twig,
    ) {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
//        dd($event->getThrowable());
        // todo: może return link do ostatniej dostępnej strony?
        if (!$this->isCorrectModule($event)) {
            return;
        }

        $event->setResponse(
            new Response(
                $this->twig->render($this->getTemplate($event), ['exception' => $event->getThrowable()]),
                $this->getStatusCode($event),
            ),
        );
    }

    private function isCorrectModule(ExceptionEvent $event): bool
    {
        $path = $event->getRequest()->getPathInfo();

        return str_starts_with($path, '/chat');
    }

    private function getStatusCode(ExceptionEvent $event): int
    {
        $exception = $event->getThrowable();

        return match (true) {
            $exception instanceof HttpExceptionInterface => $exception->getStatusCode(),
            $exception instanceof AccessDeniedException => Response::HTTP_FORBIDDEN,
            default => $exception->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR,
        };
    }

    private function getTemplate(ExceptionEvent $event): string
    {
        $exception = $event->getThrowable();

        return '@Chat/exception/' . match (true) {
            $exception instanceof AccessDeniedException,
                $exception instanceof AccessDeniedHttpException => 'error403.html.twig',
            default => 'default.html.twig',
        };
    }
}

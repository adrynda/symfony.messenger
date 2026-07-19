<?php

declare(strict_types=1);

namespace App\Core\UserInterface\Controller;

use App\Core\UserInterface\DTO\TurboStreamDTO;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\Turbo\TurboBundle;

/** @deprecated Do usunięcia najprawdopodobniej */
abstract class AbstractTurboController extends AbstractCoreController
{
    protected function turboRedirect(string $route, array $params = []): Response
    {
        if ($this->isTurboStreamFormat()) {
            $this->request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return $this->render('core/common/turbo/redirect.html.twig', [
                'redirect' => $this->generateUrl($route, $params),
            ]);
        }

        throw new \LogicException('exception.turbo.redirect.called_outside_turbo_stream');
    }

    protected function renderTurboStream(TurboStreamDTO $DTO, array $parameters = []): Response
    {
        if ($this->isTurboStreamFormat()) {
            $this->request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            $parameters['turbo'] = $DTO;
            return $this->render(
                'core/common/turbo/stream.html.twig',
                $parameters,
            );
        }

        throw new \LogicException('exception.turbo.render.rendering_outside_turbo_stream');
    }

    protected function isTurboStreamFormat(): bool
    {
        return $this->request->getPreferredFormat() === TurboBundle::STREAM_FORMAT;
    }
}

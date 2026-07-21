<?php

declare(strict_types=1);

namespace Olek\Audit\Bridge\Symfony;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\Yaml\Yaml;

final class AuditBundle extends AbstractBundle
{
    public function getPath(): string
    {
        return __DIR__;
    }

    public function loadExtension(
        array $config,
        ContainerConfigurator $container,
        ContainerBuilder $builder,
    ): void {
        $container->import($this->configPath() . '/services.yaml');
    }

    public function prependExtension(
        ContainerConfigurator $container,
        ContainerBuilder $builder,
    ): void {
        $this->doctrine($builder);
        $this->messenger($builder);
    }

    private function configPath(): string
    {
        return $this->getPath() . '/Resources/config';
    }

    private function doctrine(ContainerBuilder $builder): void
    {
        $config = Yaml::parseFile($this->configPath() . '/packages/doctrine.yaml')['doctrine'];
        $config['orm']['mappings']['Audit']['dir'] = \dirname($this->getPath(), 2) . '/Entity';

        $builder->prependExtensionConfig('doctrine', $config);
    }

    private function messenger(ContainerBuilder $builder): void
    {
        $builder->prependExtensionConfig(
            'framework',
            Yaml::parseFile($this->configPath() . '/packages/messenger.yaml')['framework'],
        );
    }
}

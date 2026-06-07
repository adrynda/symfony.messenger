<?php

declare(strict_types=1);

namespace App\Chat;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\Yaml\Yaml;

class ChatBundle extends AbstractBundle
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
        $configPath = $this->getPath() . '/Infrastructure/Resources/config';

        $container->import($configPath . '/services.yaml');
    }

    public function prependExtension(
        ContainerConfigurator $container,
        ContainerBuilder $builder,
    ): void {
        $this->doctrine($builder);
        $this->messenger($builder);
        $this->twig($builder);
        $this->security($builder);
        $this->translation($builder);
    }

    private function configPath(): string
    {
        return $this->getPath() . '/Infrastructure/Resources/config';
    }

    private function doctrine(ContainerBuilder $builder): void
    {
        $config = Yaml::parseFile($this->configPath() . '/packages/doctrine.yaml')['doctrine'];
        $config['orm']['mappings']['Chat']['dir'] = $this->getPath() . '/Domain/Model';

        $builder->prependExtensionConfig(
            'doctrine',
            $config,
        );
    }

    private function messenger(ContainerBuilder $builder): void
    {
        $builder->prependExtensionConfig(
            'framework',
            Yaml::parseFile($this->configPath() . '/packages/messenger.yaml')['framework'],
        );
    }

    private function twig(ContainerBuilder $builder): void
    {
        $builder->prependExtensionConfig(
            'twig',
            Yaml::parseFile($this->configPath() . '/packages/twig.yaml')['twig'],
        );
    }

    private function security(ContainerBuilder $builder): void
    {
        $builder->prependExtensionConfig(
            'security',
            Yaml::parseFile($this->configPath() . '/packages/security.yaml')['security'],
        );
    }

    private function translation(ContainerBuilder $builder): void
    {
        $translationsPath = $this->getPath() . '/Infrastructure/Resources/translations';
        $paths = glob($translationsPath . '/*', GLOB_ONLYDIR);
        $paths[] = $translationsPath;

        $builder->prependExtensionConfig('framework', [
            'translator' => [
                'paths' => $paths,
            ],
        ]);
    }
}

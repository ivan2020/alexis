<?php

namespace Rithis\LoginzaBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class RithisLoginzaExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $container
            ->getDefinition('security.authentication.listener.loginza')
            ->addArgument($config)
        ;

        $container
            ->getDefinition('security.authentication.loginza_entry_point')
            ->addArgument($config)
        ;

        $container->setParameter('loginza.redirect_url', $config['redirect_url']);
    }
}

<?php

namespace Rithis\LoginzaBundle\DependencyInjection\Security\Factory;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

class LoginzaFactory implements SecurityFactoryInterface
{
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        return array(
            $this->createProvider($container, $id, $userProvider),
            $this->createListener($container, $id),
            $this->createEntryPoint($container, $id, $defaultEntryPoint),
        );
    }

    private function createProvider(ContainerBuilder $container, $id, $userProvider)
    {
        $providerId = 'security.authentication.provider.loginza.' . $id;
        $container
            ->setDefinition($providerId, new DefinitionDecorator('security.authentication.provider.loginza'))
            ->addArgument(new Reference($userProvider))
        ;

        return $providerId;
    }

    private function createListener(ContainerBuilder $container, $id)
    {
        $listenerId = 'security.authentication.listener.loginza.' . $id;
        $container->setDefinition($listenerId, new DefinitionDecorator('security.authentication.listener.loginza'));

        return $listenerId;
    }

    private function createEntryPoint(ContainerBuilder $container, $id, $defaultEntryPoint)
    {
        if (null !== $defaultEntryPoint) {
            return $defaultEntryPoint;
        }

        $entryPointId = 'security.authentication.loginza_entry_point.' . $id;
        $container->setDefinition($entryPointId, new DefinitionDecorator('security.authentication.loginza_entry_point'));

        return $entryPointId;
    }

    public function getPosition()
    {
        return 'http';
    }

    public function getKey()
    {
        return 'loginza';
    }

    public function addConfiguration(NodeDefinition $node)
    {
    }
}

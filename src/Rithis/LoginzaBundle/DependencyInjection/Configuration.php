<?php

namespace Rithis\LoginzaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('rithis_loginza');

        $rootNode
            ->children()
                ->scalarNode('widget_id')->isRequired()->end()
                ->scalarNode('secret_key')->isRequired()->end()
                ->scalarNode('redirect_url')->isRequired()->end()
                ->booleanNode('is_secure')->defaultTrue()->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

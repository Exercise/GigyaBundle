<?php

namespace Exercise\GigyaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('exercise_gigya');

        $rootNode
            ->children()
                ->scalarNode('api_key')->isRequired()->end()
                ->scalarNode('secret_key')->isRequired()->end()
                ->enumNode('login_identifier')
                    ->values(array('username', 'email'))
                    ->defaultValue(null)
                ->end()
            ->end();

        return $treeBuilder;
    }
}

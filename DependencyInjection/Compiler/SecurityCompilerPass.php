<?php

namespace Exercise\GigyaBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class SecurityCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('exercise.gigya.event_listener.security')) {
            return;
        }

        $listenerDefinition = $container->getDefinition('exercise.gigya.event_listener.security');
        if ($container->hasDefinition('doctrine.orm.default_entity_manager')) {
            $listenerDefinition->replaceArgument(2, new Reference('doctrine.orm.default_entity_manager'));
        } elseif ($container->hasDefinition('doctrine.odm.mongodb.document_manager')) {
            $listenerDefinition->addArgument(2, new Reference('doctrine.odm.mongodb.document_manager'));
        }
    }
}

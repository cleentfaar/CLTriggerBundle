<?php

namespace CL\Bundle\TriggerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterParameterHandlersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('cl_trigger.util.parameter_handler_registry');

        foreach ($container->findTaggedServiceIds('cl_trigger.parameter_handler') as $id => $tags) {
            $definition->addMethodCall('register', [new Reference($id)]);
        }
    }
}

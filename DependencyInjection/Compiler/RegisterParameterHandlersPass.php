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
        $tag        = 'cl_trigger.parameter_handler';

        foreach ($container->findTaggedServiceIds($tag) as $id => $tags) {
            foreach ($tags as $tagAttributes) {
                if (!isset($tagAttributes['parameter'])) {
                    throw new \InvalidArgumentException(sprintf(
                        'Services marked with the tag %s must define a value for the `%s` attribute',
                        $tag,
                        'parameter'
                    ));
                }

                if (!isset($tagAttributes['method'])) {
                    throw new \InvalidArgumentException(sprintf(
                        'Services marked with the tag %s must define a value for the `%s` attribute',
                        $tag,
                        'method'
                    ));
                }

                $definition->addMethodCall('register', [new Reference($id), $tagAttributes['method'], $tagAttributes['parameter']]);
            }
        }
    }
}

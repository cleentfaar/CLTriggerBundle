<?php

namespace CL\Bundle\TriggerBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class RegisterParameterHandlersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $registryId = 'cl_trigger.util.parameter_handler_registry';

        if (!$container->hasDefinition($registryId)) {
            return;
        }

        $definition         = $container->findDefinition($registryId);
        $tag                = 'cl_trigger.parameter_handler';
        $requiredAttributes = ['parameter', 'method'];

        foreach ($container->findTaggedServiceIds($tag) as $id => $tags) {
            foreach ($tags as $tagAttributes) {
                foreach ($requiredAttributes as $requiredAttribute) {
                    if (!isset($tagAttributes[$requiredAttribute])) {
                        throw new \InvalidArgumentException(sprintf(
                            'Services marked with the tag "%s" must define a value for the "%s" attribute',
                            $tag,
                            $requiredAttribute
                        ));
                    }
                }

                $definition->addMethodCall('register', [new Reference($id), $tagAttributes['method'], $tagAttributes['parameter']]);
            }
        }
    }
}

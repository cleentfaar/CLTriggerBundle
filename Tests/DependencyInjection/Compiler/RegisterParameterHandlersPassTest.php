<?php

namespace CL\Bundle\TriggerBundle\Tests\DependencyInjection\Compiler;

use CL\Bundle\TriggerBundle\DependencyInjection\Compiler\RegisterParameterHandlersPass;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class MyCompilerPassTest extends AbstractCompilerPassTestCase
{
    protected function registerCompilerPass(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterParameterHandlersPass());
    }

    public function testIfCompilerPassCollectsServicesByTagsTheseWillExist()
    {
        $method    = 'onFoo';
        $parameter = 'foo';

        $collectingService = new Definition('CL\Bundle\TriggerBundle\Util\ParameterHandlerRegistry');
        $this->setDefinition('cl_trigger.util.parameter_handler_registry', $collectingService);

        $collectedService = new Definition('CL\Bundle\TriggerBundle\Test\MockHandler');
        $collectedService->addTag('cl_trigger.parameter_handler', ['method' => $method, 'parameter' => $parameter]);
        $this->setDefinition('collected_service', $collectedService);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'cl_trigger.util.parameter_handler_registry',
            'register',
            [
                new Reference('collected_service'),
                $method,
                $parameter,
            ]
        );
    }
}

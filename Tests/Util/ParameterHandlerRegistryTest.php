<?php

namespace CL\Bundle\TriggerBundle\Tests\Util;

use CL\Bundle\TriggerBundle\Spec\ParameterBagHandlerInterface;
use CL\Bundle\TriggerBundle\Spec\ParameterHandlerInterface;
use CL\Bundle\TriggerBundle\Util\ParameterHandlerRegistry;

class ParameterHandlerRegistryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ParameterHandlerRegistry
     */
    private $parameterHandlerRegistry;

    protected function setUp()
    {
        $this->parameterHandlerRegistry = new ParameterHandlerRegistry();
    }

    public function testParameter()
    {
        /** @var ParameterHandlerInterface $parameterHandler */
        $parameterHandler = $this->getMock('CL\Bundle\TriggerBundle\Spec\ParameterHandlerInterface');
        $this->parameterHandlerRegistry->registerParameterHandler($parameterHandler, 'foo');

        $this->assertContains($parameterHandler, $this->parameterHandlerRegistry->getParameterHandlers('foo'));
    }

    public function testParameterBag()
    {
        /** @var ParameterBagHandlerInterface $parameterBagHandler */
        $parameterBagHandler = $this->getMock('CL\Bundle\TriggerBundle\Spec\ParameterBagHandlerInterface');
        $this->parameterHandlerRegistry->registerParameterBagHandler($parameterBagHandler);

        $this->assertContains($parameterBagHandler, $this->parameterHandlerRegistry->getParameterBagHandlers());
    }
}

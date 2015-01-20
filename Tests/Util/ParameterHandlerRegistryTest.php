<?php

namespace CL\Bundle\TriggerBundle\Tests\Util;

use CL\Bundle\TriggerBundle\Tests\AbstractTestCase;
use CL\Bundle\TriggerBundle\Util\ParameterHandlerRegistry;

class ParameterHandlerRegistryTest extends AbstractTestCase
{
    /**
     * @var ParameterHandlerRegistry
     */
    private $parameterHandlerRegistry;

    protected function setUp()
    {
        $this->parameterHandlerRegistry = new ParameterHandlerRegistry();
    }

    public function testRegister()
    {
        $parameter        = 'foo';
        $method           = 'onFoo';
        $parameterHandler = $this->getHandlerMock($parameter);

        $this->parameterHandlerRegistry->register($parameterHandler, $method, $parameter);

        $this->assertContains([$parameterHandler, $method], $this->parameterHandlerRegistry->getHandlers($parameter));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The given parameter handler must be an object, got: string
     */
    public function testRegisterNonObject()
    {
        $parameter        = 'foo';
        $method           = 'onFoo';
        $parameterHandler = 'non-object';

        $this->parameterHandlerRegistry->register($parameterHandler, $method, $parameter);

        $this->assertContains([$parameterHandler, $method], $this->parameterHandlerRegistry->getHandlers($parameter));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The given parameter handler (stdClass) does not have that method: onFoo
     */
    public function testRegisterNonExistingMethod()
    {
        $parameter        = 'foo';
        $method           = 'onFoo';
        $parameterHandler = new \stdClass();

        $this->parameterHandlerRegistry->register($parameterHandler, $method, $parameter);

        $this->assertContains([$parameterHandler, $method], $this->parameterHandlerRegistry->getHandlers($parameter));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The parameter to trigger the handler must be a string, got: array
     */
    public function testRegisterNonStringParameter()
    {
        $parameter        = 'nonstring';
        $method           = 'onFoo';
        $parameterHandler = $this->getHandlerMock($parameter);

        $this->parameterHandlerRegistry->register($parameterHandler, $method, [$parameter]);

        $this->assertContains([$parameterHandler, $method], $this->parameterHandlerRegistry->getHandlers($parameter));
    }
}

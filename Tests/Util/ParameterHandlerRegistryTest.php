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

    public function testParameter()
    {
        $parameter        = 'foo';
        $method           = 'onFoo';
        $parameterHandler = $this->getHandlerMock($this->parameterHandlerRegistry, $parameter);

        $this->assertContains([$parameterHandler, $method], $this->parameterHandlerRegistry->getHandlers($parameter));
    }
}

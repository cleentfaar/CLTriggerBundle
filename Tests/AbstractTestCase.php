<?php

namespace CL\Bundle\TriggerBundle\Tests;

use CL\Bundle\TriggerBundle\Util\ParameterHandlerRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;

abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @param ParameterHandlerRegistry $parameterHandlerRegistry
     * @param string                   $parameter
     * @param string|null              $redirectUrl
     *
     * @return object
     */
    protected function getHandlerMock(ParameterHandlerRegistry $parameterHandlerRegistry, $parameter = 'foo', $redirectUrl = null)
    {
        $parameterHandlerMock = $this->getMock('CL\Bundle\TriggerBundle\Test\MockHandler');
        $method               = 'on' . ucfirst($parameter);

        if ($redirectUrl !== null) {
            $parameterHandlerMock->expects($this->any())->method($method)->willReturn(new RedirectResponse($redirectUrl));
        }

        $parameterHandlerRegistry->register($parameterHandlerMock, $method, $parameter);

        return $parameterHandlerMock;
    }
}

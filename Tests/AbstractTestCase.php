<?php

namespace CL\Bundle\TriggerBundle\Tests;

use CL\Bundle\TriggerBundle\Util\ParameterHandlerRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;

abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string                        $parameter
     * @param string|null                   $redirectUrl
     * @param ParameterHandlerRegistry|null $parameterHandlerRegistry
     *
     * @return object
     */
    protected function getHandlerMock($parameter = 'foo', $redirectUrl = null, ParameterHandlerRegistry $parameterHandlerRegistry = null)
    {
        $parameterHandlerMock = $this->getMock('CL\Bundle\TriggerBundle\Test\MockHandler');
        $method               = 'on' . ucfirst($parameter);

        if ($redirectUrl !== null) {
            $parameterHandlerMock->expects($this->any())->method($method)->willReturn(new RedirectResponse($redirectUrl));
        }

        if ($parameterHandlerRegistry !== null) {
            $parameterHandlerRegistry->register($parameterHandlerMock, $method, $parameter);
        }

        return $parameterHandlerMock;
    }
}

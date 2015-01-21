<?php

namespace CL\Bundle\TriggerBundle\Tests;

use CL\Bundle\TriggerBundle\Util\ParameterHandlerRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    const BASE_URI = '/test';

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

    /**
     * @param       $method
     * @param array $data
     * @param       $uri
     *
     * @return Request
     */
    protected function createRequest($method = Request::METHOD_GET, array $data, $uri = self::BASE_URI)
    {
        return Request::create($uri, $method, $data);
    }

    /**
     * @param array  $query
     * @param string $uri
     *
     * @return Request
     */
    protected function createGetRequest(array $query = null, $uri = self::BASE_URI)
    {
        if ($query === null) {
            $request = $this->createRequest(Request::METHOD_GET, [], $uri);
        } else {
            $request = $this->createRequest(Request::METHOD_GET, $query, $uri);
        }

        return $request;
    }
}

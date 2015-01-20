<?php

namespace CL\Bundle\TriggerBundle\Tests\EventListener;

use CL\Bundle\TriggerBundle\EventListener\ParameterListener;
use CL\Bundle\TriggerBundle\Tests\AbstractTestCase;
use CL\Bundle\TriggerBundle\Util\ParameterHandlerRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ParameterListenerTest extends AbstractTestCase
{
    /**
     * @var ParameterListener
     */
    private $parameterListener;

    /**
     * @var ParameterHandlerRegistry
     */
    private $parameterHandlerRegistry;

    protected function setUp()
    {
        $this->parameterHandlerRegistry = new ParameterHandlerRegistry();
        $this->parameterListener        = new ParameterListener($this->parameterHandlerRegistry);
    }

    public function testOnRequestWithSubRequest()
    {
        $event = $this->createGetResponseEvent([], Request::METHOD_GET, HttpKernel::SUB_REQUEST);

        $this->parameterListener->onRequest($event);

        $this->assertNull($event->getResponse());
    }

    public function testOnRequestWithNonGetMethod()
    {
        $this->getHandlerMock('foo', parent::BASE_URI, $this->parameterHandlerRegistry);

        $event = $this->createGetResponseEvent([], Request::METHOD_POST);

        $this->parameterListener->onRequest($event);

        $response = $event->getResponse();

        $this->assertNull($response);
    }

    public function testOnRequestWithMatchingParameterHandler()
    {
        $this->getHandlerMock('foo', parent::BASE_URI, $this->parameterHandlerRegistry);

        $event = $this->createGetResponseEvent(['foo' => 'bar']);

        $this->parameterListener->onRequest($event);

        /** @var RedirectResponse $response */
        $response = $event->getResponse();

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $response);
        $this->assertEquals(parent::BASE_URI, $response->getTargetUrl());
    }

    public function testOnRequestWithoutMatchingParameterHandler()
    {
        $this->getHandlerMock('foo', parent::BASE_URI, $this->parameterHandlerRegistry);

        $event = $this->createGetResponseEvent(['apple' => 'pie']);

        $this->parameterListener->onRequest($event);

        $response = $event->getResponse();

        $this->assertNull($response);
    }

    public function testOnRequestWithMultipleMatchingParameterHandlers()
    {
        $this->getHandlerMock('foo', parent::BASE_URI . '?apple=pie', $this->parameterHandlerRegistry);
        $this->getHandlerMock('apple', parent::BASE_URI . '?foo=bar', $this->parameterHandlerRegistry);

        $event = $this->createGetResponseEvent(['foo' => 'bar', 'apple' => 'pie']);

        $this->parameterListener->onRequest($event);

        /** @var RedirectResponse $response */
        $response = $event->getResponse();

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $response);
        $this->assertEquals(parent::BASE_URI . '?apple=pie', $response->getTargetUrl());
    }

    /**
     * @param array  $query
     * @param string $method
     *
     * @return GetResponseEvent
     */
    private function createGetResponseEvent(array $query = [], $method = Request::METHOD_GET, $requestType = HttpKernel::MASTER_REQUEST)
    {
        /** @var HttpKernelInterface|\PHPUnit_Framework_MockObject_MockObject $kernel */
        $kernel  = $this->getMock('Symfony\Component\HttpKernel\HttpKernelInterface');
        $request = Request::create(parent::BASE_URI, $method, $query);
        $event   = new GetResponseEvent($kernel, $request, $requestType);

        return $event;
    }
}

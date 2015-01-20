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
    const BASE_URI = '/test';

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
        $this->getHandlerMock($this->parameterHandlerRegistry, 'foo', self::BASE_URI);

        $event = $this->createGetResponseEvent([], Request::METHOD_POST);

        $this->parameterListener->onRequest($event);

        $response = $event->getResponse();

        $this->assertNull($response);
    }

    public function testOnRequestWithMatchingParameterHandler()
    {
        $this->getHandlerMock($this->parameterHandlerRegistry, 'foo', self::BASE_URI);

        $event = $this->createGetResponseEvent(['foo' => 'bar']);

        $this->parameterListener->onRequest($event);

        /** @var RedirectResponse $response */
        $response = $event->getResponse();

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $response);
        $this->assertEquals(self::BASE_URI, $response->getTargetUrl());
    }

    public function testOnRequestWithoutMatchingParameterHandler()
    {
        $this->getHandlerMock($this->parameterHandlerRegistry, 'foo', self::BASE_URI);

        $event = $this->createGetResponseEvent(['apple' => 'pie']);

        $this->parameterListener->onRequest($event);

        $response = $event->getResponse();

        $this->assertNull($response);
    }

    public function testOnRequestWithMultipleMatchingParameterHandlers()
    {
        $this->getHandlerMock($this->parameterHandlerRegistry, 'foo', self::BASE_URI . '?apple=pie');
        $this->getHandlerMock($this->parameterHandlerRegistry, 'apple', self::BASE_URI . '?foo=bar');

        $event = $this->createGetResponseEvent(['foo' => 'bar', 'apple' => 'pie']);

        $this->parameterListener->onRequest($event);

        /** @var RedirectResponse $response */
        $response = $event->getResponse();

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $response);
        $this->assertEquals(self::BASE_URI . '?apple=pie', $response->getTargetUrl());
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
        $request = Request::create(self::BASE_URI, $method, $query);
        $event   = new GetResponseEvent($kernel, $request, $requestType);

        return $event;
    }
}

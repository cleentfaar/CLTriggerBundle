<?php

namespace CL\Bundle\TriggerBundle\Tests\EventListener;

use CL\Bundle\TriggerBundle\EventListener\ParameterListener;
use CL\Bundle\TriggerBundle\Spec\ParameterBagHandlerInterface;
use CL\Bundle\TriggerBundle\Spec\ParameterHandlerInterface;
use CL\Bundle\TriggerBundle\Util\ParameterHandlerRegistry;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\WebTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ParameterListenerTest extends WebTestCase
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
        $this->createParameterHandlerMock('foo', self::BASE_URI);

        $event = $this->createGetResponseEvent([], Request::METHOD_POST);

        $this->parameterListener->onRequest($event);

        $response = $event->getResponse();

        $this->assertNull($response);
    }

    public function testOnRequestWithMatchingParameterHandler()
    {
        $this->createParameterHandlerMock('foo', self::BASE_URI);

        $event = $this->createGetResponseEvent(['foo' => 'bar']);

        $this->parameterListener->onRequest($event);

        /** @var RedirectResponse $response */
        $response = $event->getResponse();

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $response);
        $this->assertEquals(self::BASE_URI, $response->getTargetUrl());
    }

    public function testOnRequestWithoutMatchingParameterHandler()
    {
        $this->createParameterHandlerMock('foo', self::BASE_URI);

        $event = $this->createGetResponseEvent(['apple' => 'pie']);

        $this->parameterListener->onRequest($event);

        $response = $event->getResponse();

        $this->assertNull($response);
    }

    public function testOnRequestWithMatchingParameterBagHandler()
    {
        $this->createParameterBagHandlerMock(self::BASE_URI);

        $event = $this->createGetResponseEvent(['foo' => 'bar']);

        $this->parameterListener->onRequest($event);

        /** @var RedirectResponse $response */
        $response = $event->getResponse();

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $response);
        $this->assertEquals(self::BASE_URI, $response->getTargetUrl());
    }

    public function testOnRequestWithoutMatchingParameterBagHandler()
    {
        $this->createParameterBagHandlerMock(self::BASE_URI);

        $event = $this->createGetResponseEvent([], Request::METHOD_POST);

        $this->parameterListener->onRequest($event);

        $response = $event->getResponse();

        $this->assertNull($response);
    }

    public function testOnRequestWithMultipleMatchingParameterHandlers()
    {
        $this->createParameterHandlerMock('foo', self::BASE_URI . '?apple=pie');
        $this->createParameterHandlerMock('apple', self::BASE_URI . '?foo=bar');

        $event = $this->createGetResponseEvent(['foo' => 'bar', 'apple' => 'pie']);

        $this->parameterListener->onRequest($event);

        /** @var RedirectResponse $response */
        $response = $event->getResponse();

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $response);
        $this->assertEquals(self::BASE_URI . '?apple=pie', $response->getTargetUrl());
    }

    /**
     * @return ParameterHandlerInterface|\PHPUnit_Framework_MockObject_MockObject $parameterHandlerMock
     */
    private function createParameterHandlerMock($parameter, $redirectUrl = null)
    {
        /** @var ParameterHandlerInterface|\PHPUnit_Framework_MockObject_MockObject $parameterHandlerMock */
        $parameterHandlerMock = $this->getMock('CL\Bundle\TriggerBundle\Spec\ParameterHandlerInterface');

        if ($redirectUrl !== null) {
            $parameterHandlerMock->expects($this->any())->method('onTrigger')->willReturn(new RedirectResponse($redirectUrl));
        }

        $this->parameterHandlerRegistry->registerParameterHandler($parameterHandlerMock, 'foo');

        return $parameterHandlerMock;
    }

    /**
     * @return ParameterBagHandlerInterface|\PHPUnit_Framework_MockObject_MockObject $parameterHandlerMock
     */
    private function createParameterBagHandlerMock($redirectUrl = null)
    {
        /** @var ParameterBagHandlerInterface|\PHPUnit_Framework_MockObject_MockObject $parameterBagHandlerMock */
        $parameterBagHandlerMock = $this->getMock('CL\Bundle\TriggerBundle\Spec\ParameterBagHandlerInterface');

        if ($redirectUrl !== null) {
            $parameterBagHandlerMock->expects($this->any())->method('onTrigger')->willReturn(new RedirectResponse($redirectUrl));
        }

        $this->parameterHandlerRegistry->registerParameterBagHandler($parameterBagHandlerMock);

        return $parameterBagHandlerMock;
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

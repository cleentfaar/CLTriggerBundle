<?php

namespace CL\Bundle\TriggerBundle\Tests\EventListener;

use CL\Bundle\TriggerBundle\EventListener\ParameterListener;
use CL\Bundle\TriggerBundle\Spec\ParameterHandlerInterface;
use CL\Bundle\TriggerBundle\Util\ParameterHandlerRegistry;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\WebTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;

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

    public function testOnKernelRequestWithSubRequest()
    {
        $event = $this->createGetResponseEvent([], HttpKernel::SUB_REQUEST);

        $this->parameterListener->onKernelRequest($event);

        $this->assertNull($event->getResponse());
    }

    public function testOnKernelRequestWithMatchingParameterHandler()
    {
        $this->createParameterHandlerMock('foo', self::BASE_URI);

        $event = $this->createGetResponseEvent(['foo' => 'bar']);

        $this->parameterListener->onKernelRequest($event);

        /** @var RedirectResponse $response */
        $response = $event->getResponse();

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $response);
        $this->assertEquals(self::BASE_URI, $response->getTargetUrl());
    }

    public function testOnKernelRequestWithoutMatchingParameterHandler()
    {
        $this->createParameterHandlerMock('foo', self::BASE_URI);

        $event = $this->createGetResponseEvent(['apple' => 'pie']);

        $this->parameterListener->onKernelRequest($event);

        $response = $event->getResponse();

        $this->assertNull($response);
    }

    public function testOnKernelRequestWithMultipleMatchingParameterHandlers()
    {
        $this->createParameterHandlerMock('foo', self::BASE_URI . '?apple=pie');
        $this->createParameterHandlerMock('apple', self::BASE_URI . '?foo=bar');

        $event = $this->createGetResponseEvent(['foo' => 'bar', 'apple' => 'pie']);

        $this->parameterListener->onKernelRequest($event);

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
            $parameterHandlerMock->expects($this->any())->method('onParameter')->willReturn(new RedirectResponse($redirectUrl));
        }

        $this->parameterHandlerRegistry->registerParameterHandler($parameterHandlerMock, 'foo');

        return $parameterHandlerMock;
    }

    /**
     * @param array $query
     * @param int   $requestType
     *
     * @return GetResponseEvent
     */
    private function createGetResponseEvent(array $query = [], $requestType = HttpKernel::MASTER_REQUEST)
    {
        $request = Request::create(self::BASE_URI, 'GET', $query);
        $event   = new GetResponseEvent($this->getMock('Symfony\Component\HttpKernel\HttpKernelInterface'), $request, $requestType);

        return $event;
    }
}

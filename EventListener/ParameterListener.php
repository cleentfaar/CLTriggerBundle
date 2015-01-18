<?php

namespace CL\Bundle\TriggerBundle\EventListener;

use CL\Bundle\TriggerBundle\Util\ParameterHandlerInterface;
use CL\Bundle\TriggerBundle\Util\ParameterHandlerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;

class ParameterListener
{
    /**
     * @var ParameterHandlerRegistry
     */
    private $parameterHandlerRegistry;

    /**
     * @param ParameterHandlerRegistry $parameterHandlerRegistry
     */
    public function __construct(ParameterHandlerRegistry $parameterHandlerRegistry)
    {
        $this->parameterHandlerRegistry = $parameterHandlerRegistry;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();

        if ($request->isMethod('POST') || $request->query->count() < 1) {
            return;
        }

        foreach ($this->parameterHandlerRegistry->all() as $handler) {
            $this->handle($handler, $request, $event);
        }

        if ($event->getResponse()) {
            $event->stopPropagation();
        }
    }

    /**
     * @param ParameterHandlerInterface $parameterHandler
     * @param Request                   $request
     * @param GetResponseEvent          $event
     */
    private function handle(ParameterHandlerInterface $parameterHandler, Request $request, GetResponseEvent $event)
    {
        $parameterHandler->setRequest($request);

        $returnValue = $parameterHandler->doHandle($request->query);

        if (null !== $returnValue) {
            if ($returnValue instanceof Response) {
                $event->setResponse($returnValue);

                return;
            }

            throw new \RuntimeException(sprintf(
                'Parameter handlers must return null or a Response, got %s',
                gettype($returnValue) === 'object' ? 'instance of '.get_class($returnValue) : gettype($returnValue)
            ));
        }
    }
}

<?php

namespace CL\Bundle\TriggerBundle\EventListener;

use CL\Bundle\TriggerBundle\Util\ParameterHandlerRegistry;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

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

        $request  = $event->getRequest();
        $response = $event->getResponse();

        if ($response !== null || $request->isMethod('POST') || $request->query->count() < 1) {
            return;
        }

        foreach ($this->parameterHandlerRegistry->getBagHandlers() as $handler) {
            $response = $handler->onQuery($request->query, $event->getRequest());

            if ($response !== null) {
                $event->setResponse($response);

                break;
            }
        }

        if ($response === null) {
            foreach ($request->query->all() as $key => $value) {
                foreach ($this->parameterHandlerRegistry->getParameterHandlers($key) as $handler) {
                    $response = $handler->onParameter($key, $value, $event->getRequest());

                    if ($response !== null) {
                        $event->setResponse($response);

                        break;
                    }
                }
            }
        }

        if ($event->getResponse()) {
            $event->stopPropagation();
        }
    }
}

<?php

namespace CL\Bundle\TriggerBundle\EventListener;

use CL\Bundle\TriggerBundle\Util\ParameterHandlerRegistry;
use CL\Bundle\TriggerBundle\Util\RedirectHelper;
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
    public function onRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request  = $event->getRequest();
        $response = $event->getResponse();

        if ($response !== null || $request->isMethod('POST') || $request->query->count() < 1) {
            return;
        }

        $this->tryParameterHandlers($event);

        if ($event->getResponse()) {
            // fasten the response
            $event->stopPropagation();
        }
    }

    /**
     * @param GetResponseEvent $event
     */
    private function tryParameterHandlers(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        foreach ($request->query->all() as $key => $value) {
            foreach ($this->parameterHandlerRegistry->getHandlers($key) as $handlerData) {
                list($handler, $method) = $handlerData;

                $redirectHelper = new RedirectHelper($request, [$key]);
                $response       = $handler->{$method}($value, $redirectHelper);

                if ($response !== null) {
                    $event->setResponse($response);

                    return;
                }
            }
        }
    }
}

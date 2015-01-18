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

        $this->tryParameterBagHandlers($event);

        if ($event->getResponse() === null) {
            $this->tryParameterHandlers($event);
        }

        if ($event->getResponse()) {
            $event->stopPropagation();
        }
    }

    /**
     * @param GetResponseEvent $event
     */
    private function tryParameterBagHandlers(GetResponseEvent $event)
    {
        foreach ($this->parameterHandlerRegistry->getParameterBagHandlers() as $handler) {
            $response = $handler->onTrigger($event->getRequest()->query, $event->getRequest());

            if ($response !== null) {
                $event->setResponse($response);

                break;
            }
        }
    }

    /**
     * @param GetResponseEvent $event
     */
    private function tryParameterHandlers(GetResponseEvent $event)
    {
        foreach ($event->getRequest()->query->all() as $key => $value) {
            foreach ($this->parameterHandlerRegistry->getParameterHandlers($key) as $handler) {
                $response = $handler->onTrigger($value, $event->getRequest(), $key);

                if ($response !== null) {
                    $event->setResponse($response);

                    break 2;
                }
            }
        }
    }
}

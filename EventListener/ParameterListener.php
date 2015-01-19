<?php

namespace CL\Bundle\TriggerBundle\EventListener;

use CL\Bundle\TriggerBundle\Util\ParameterHandlerRegistry;
use CL\Bundle\TriggerBundle\Util\RedirectHelper;
use Symfony\Component\HttpFoundation\Request;
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
            $response = $handler->onTrigger($event->getRequest()->query, $this->createRedirectHelper($event->getRequest()));

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
                $response = $handler->onTrigger($value, $this->createRedirectHelper($event->getRequest(), [$key]));

                if ($response !== null) {
                    $event->setResponse($response);

                    break 2;
                }
            }
        }
    }

    /**
     * @param Request $request
     * @param array   $strippableParameters
     *
     * @return RedirectHelper
     */
    private function createRedirectHelper(Request $request, array $strippableParameters = [])
    {
        return new RedirectHelper($request, $strippableParameters);
    }
}

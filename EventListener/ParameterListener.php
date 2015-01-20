<?php

namespace CL\Bundle\TriggerBundle\EventListener;

use CL\Bundle\TriggerBundle\Util\ParameterHandlerRegistry;
use CL\Bundle\TriggerBundle\Util\RedirectHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

        $this->processHandlers($request, $event);
    }

    /**
     * @param Request          $request
     * @param GetResponseEvent $event
     */
    private function processHandlers(Request $request, GetResponseEvent $event)
    {
        foreach ($request->query->all() as $parameter => $value) {
            foreach ($this->parameterHandlerRegistry->getHandlers($parameter) as $handlerData) {
                $redirectHelper = new RedirectHelper($request, [$parameter]);
                $response       = $this->executeHandler($redirectHelper, $handlerData, $value);

                if ($response !== null) {
                    $event->setResponse($response);

                    return;
                }
            }
        }
    }

    /**
     * @param RedirectHelper $redirectHelper
     * @param array          $handlerData
     * @param string         $value
     *
     * @return Response|null
     */
    private function executeHandler(RedirectHelper $redirectHelper, array $handlerData, $value)
    {
        list($handler, $method) = $handlerData;

        return call_user_func_array([$handler, $method], [$value, $redirectHelper]);
    }
}

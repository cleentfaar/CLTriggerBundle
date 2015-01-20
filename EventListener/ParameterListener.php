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

        foreach ($request->query->all() as $key => $value) {
            foreach ($this->parameterHandlerRegistry->getHandlers($key) as $handlerData) {
                $response = $this->executeHandler($request, $handlerData, $key, $value);

                if ($response !== null) {
                    $event->setResponse($response);

                    return;
                }
            }
        }
    }

    /**
     * @param Request $request
     * @param array   $handlerData
     * @param string  $parameter
     * @param string  $value
     *
     * @return Response|null
     */
    private function executeHandler(Request $request, array $handlerData, $parameter, $value)
    {
        list($handler, $method) = $handlerData;

        $redirectHelper = new RedirectHelper($request, [$parameter]);

        return call_user_func_array([$handler, $method], [$value, $redirectHelper]);
    }
}

<?php


namespace CL\Bundle\TriggerBundle\Util;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

interface ParameterHandlerInterface
{
    /**
     * @param string           $parameter
     * @param mixed            $value
     * @param GetResponseEvent $event
     *
     * @return Response|null
     */
    public function onParameter($parameter, $value, GetResponseEvent $event);
}

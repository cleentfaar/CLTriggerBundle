<?php


namespace CL\Bundle\TriggerBundle\Util;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

interface ParameterBagHandlerInterface
{
    /**
     * @param ParameterBag $query
     *
     * @return Response|null
     */
    public function onQuery(ParameterBag $query, GetResponseEvent $event);
}

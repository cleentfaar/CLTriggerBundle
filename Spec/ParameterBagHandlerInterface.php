<?php

namespace CL\Bundle\TriggerBundle\Spec;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;

interface ParameterBagHandlerInterface
{
    /**
     * @param ParameterBag $query
     *
     * @return Response|null
     */
    public function onTrigger(ParameterBag $query);
}

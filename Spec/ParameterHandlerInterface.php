<?php

namespace CL\Bundle\TriggerBundle\Spec;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface ParameterHandlerInterface
{
    /**
     * @param mixed   $value
     * @param Request $request
     * @param string  $triggeredParameter
     *
     * @return Response|null
     */
    public function onTrigger($value, Request $request, $triggeredParameter);
}

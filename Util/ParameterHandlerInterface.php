<?php


namespace CL\Bundle\TriggerBundle\Util;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface ParameterHandlerInterface
{
    /**
     * @param string  $parameter
     * @param mixed   $value
     * @param Request $request
     *
     * @return Response|null
     */
    public function onParameter($parameter, $value, Request $request);
}

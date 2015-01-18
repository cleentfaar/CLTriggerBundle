<?php


namespace CL\Bundle\TriggerBundle\Util;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface ParameterHandlerInterface
{
    /**
     * @param Request $request
     */
    public function setRequest(Request $request);

    /**
     * @param ParameterBag $query
     *
     * @return Response|null
     */
    public function doHandle(ParameterBag $query);
}

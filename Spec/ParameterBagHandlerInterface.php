<?php


namespace CL\Bundle\TriggerBundle\Spec;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface ParameterBagHandlerInterface
{
    /**
     * @param ParameterBag $query
     * @param Request      $request
     *
     * @return Response|null
     */
    public function onQuery(ParameterBag $query, Request $request);
}

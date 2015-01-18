<?php

namespace CL\Bundle\TriggerBundle\Util;

class ParameterHandlerRegistry
{
    /**
     * @var ParameterHandlerInterface[]
     */
    private $parameterHandlers = [];

    /**
     * @var ParameterBagHandlerInterface[]
     */
    private $bagHandlers= [];

    /**
     * @param ParameterHandlerInterface $handler
     */
    public function registerParameter(ParameterHandlerInterface $handler, $parameter)
    {
        $this->parameterHandlers[$parameter] = $handler;
    }

    /**
     * @param ParameterHandlerInterface $handler
     */
    public function registerParameterBag(ParameterHandlerInterface $handler)
    {
        $this->bagHandlers[] = $handler;
    }

    /**
     * @return ParameterHandlerInterface[]
     */
    public function getParameterHandlers()
    {
        return $this->parameterHandlers;
    }

    /**
     * @return ParameterBagHandlerInterface[]
     */
    public function getBagHandlers()
    {
        return $this->bagHandlers;
    }
}

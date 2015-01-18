<?php

namespace CL\Bundle\TriggerBundle\Util;

use CL\Bundle\TriggerBundle\Spec\ParameterBagHandlerInterface;
use CL\Bundle\TriggerBundle\Spec\ParameterHandlerInterface;

class ParameterHandlerRegistry
{
    /**
     * @var ParameterHandlerInterface[]
     */
    private $parameterHandlers = [];

    /**
     * @var ParameterBagHandlerInterface[]
     */
    private $bagHandlers = [];

    /**
     * @param ParameterHandlerInterface $handler
     * @param string                    $parameter
     */
    public function registerParameter(ParameterHandlerInterface $handler, $parameter)
    {
        $this->parameterHandlers[$parameter][] = $handler;
    }

    /**
     * @param ParameterHandlerInterface $handler
     */
    public function registerParameterBag(ParameterHandlerInterface $handler)
    {
        $this->bagHandlers[] = $handler;
    }

    /**
     * @param string $parameter
     *
     * @return ParameterHandlerInterface[]
     */
    public function getParameterHandlers($parameter)
    {
        return array_key_exists($parameter, $this->parameterHandlers) ? $this->parameterHandlers[$parameter] : [];
    }

    /**
     * @return ParameterBagHandlerInterface[]
     */
    public function getBagHandlers()
    {
        return $this->bagHandlers;
    }
}

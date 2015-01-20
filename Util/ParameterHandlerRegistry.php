<?php

namespace CL\Bundle\TriggerBundle\Util;

use CL\Bundle\TriggerBundle\Spec\ParameterBagHandlerInterface;
use CL\Bundle\TriggerBundle\Spec\ParameterHandlerInterface;

class ParameterHandlerRegistry
{
    /**
     * @var [ParameterHandlerInterface[]]
     */
    private $parameterHandlers = [];

    /**
     * @var ParameterBagHandlerInterface[]
     */
    private $parameterBagHandlers = [];

    /**
     * @param ParameterHandlerInterface $handler
     * @param string                    $parameter
     */
    public function registerParameterHandler(ParameterHandlerInterface $handler, $parameter)
    {
        $this->parameterHandlers[$parameter][] = $handler;
    }

    /**
     * @param ParameterBagHandlerInterface $handler
     */
    public function registerParameterBagHandler(ParameterBagHandlerInterface $handler)
    {
        $this->parameterBagHandlers[] = $handler;
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
    public function getParameterBagHandlers()
    {
        return $this->parameterBagHandlers;
    }
}

<?php

namespace CL\Bundle\TriggerBundle\Util;

class ParameterHandlerRegistry
{
    /**
     * @var ParameterHandlerInterface[]
     */
    private $handlers = [];

    /**
     * @param ParameterHandlerInterface $handler
     */
    public function register(ParameterHandlerInterface $handler)
    {
        $this->handlers[] = $handler;
    }

    /**
     * @return ParameterHandlerInterface[]
     */
    public function all()
    {
        return $this->handlers;
    }
}

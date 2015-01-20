<?php

namespace CL\Bundle\TriggerBundle\Util;

class ParameterHandlerRegistry
{
    /**
     * @var array
     */
    private $parameterHandlers = [];

    /**
     * @param object $handler
     * @param string $method
     * @param string $parameter
     */
    public function register($handler, $method, $parameter)
    {
        if (!is_object($handler)) {
            throw new \InvalidArgumentException(sprintf(
                'Parameter handler must be an object, got: %s',
                gettype($handler)
            ));
        }

        if (!method_exists($handler, $method)) {
            throw new \InvalidArgumentException(sprintf(
                'The parameter handler (%s) does not have the method: %s',
                get_class($handler),
                $method
            ));
        }

        $this->parameterHandlers[$parameter][] = [$handler, $method];
    }

    /**
     * @param $parameter
     *
     * @return array
     */
    public function getHandlers($parameter)
    {
        return array_key_exists($parameter, $this->parameterHandlers) ? $this->parameterHandlers[$parameter] : [];
    }
}

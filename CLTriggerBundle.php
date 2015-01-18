<?php

namespace CL\Bundle\TriggerBundle;

use CL\Bundle\TriggerBundle\DependencyInjection\Compiler\RegisterParameterHandlersPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CLTriggerBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RegisterParameterHandlersPass());
    }
}

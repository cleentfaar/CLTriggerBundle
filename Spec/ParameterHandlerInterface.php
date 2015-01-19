<?php

namespace CL\Bundle\TriggerBundle\Spec;

use CL\Bundle\TriggerBundle\Util\RedirectHelper;
use Symfony\Component\HttpFoundation\Response;

interface ParameterHandlerInterface
{
    /**
     * @param mixed          $value
     * @param RedirectHelper $redirectHelper
     *
     * @return Response|null
     */
    public function onTrigger($value, RedirectHelper $redirectHelper);
}

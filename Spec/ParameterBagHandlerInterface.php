<?php

namespace CL\Bundle\TriggerBundle\Spec;

use CL\Bundle\TriggerBundle\Util\RedirectHelper;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;

interface ParameterBagHandlerInterface
{
    /**
     * @param ParameterBag   $query
     * @param RedirectHelper $redirectHelper
     *
     * @return Response|null
     */
    public function onTrigger(ParameterBag $query, RedirectHelper $redirectHelper);
}

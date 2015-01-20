<?php

namespace CL\Bundle\TriggerBundle\Test;

use CL\Bundle\TriggerBundle\Util\RedirectHelper;

class MockHandler
{
    public function onFoo($value, RedirectHelper $redirectHelper)
    {
        // do something foo-ey!
    }

    public function onApple($value, RedirectHelper $redirectHelper)
    {
        // do something foo-ey!
    }
}

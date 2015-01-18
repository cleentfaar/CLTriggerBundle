# Usage

Let's say you want to execute some business logic whenever someone visits the url `/?foobar=1`.

First, create a service that will handle incoming GET-requests

```php
<?php

namespace CL\Bundle\DemoBundle\EventListener;

use CL\Bundle\TriggerBundle\Util\AbstractParameterHandler;
use Symfony\Component\HttpFoundation\ParameterBag;

class AcmeParameterHandler extends AbstractParameterHandler
{
    /**
     * {@inheritdoc}
     */
    public function doHandle(ParameterBag $query)
    {
        if ($query->has('foobar')) {
            // do something awesome...

            return $this->createRedirect(['foobar']);
        }
    }
}
```

In the example above a `RedirectResponse` is returned to indicate the request should be redirected
immediately after all parameter-handlers have handled the request.

All that is left is to register this service (exact location depends on your setup):
```yml
# app/config/services.yml
services:
  app.parameter_handler:
    class: AppBundle\EventListener\ParameterHandler
    tags:
      - { name: cl_trigger.parameter_handler }
```

The use of tags by this bundle allow you to register multiple handlers to handle different scenario's,
even for the same request.


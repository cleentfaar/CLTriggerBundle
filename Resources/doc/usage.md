# Usage

- [Handling a single parameter](#handling-a-single-parameter)
- [Handling a parameter and redirecting to an URL without the parameter](#handling-a-parameter-and-redirecting-to-an-url-without-the-parameter)


### Handling a single parameter

Let's say you want to execute some business logic whenever someone visits your site with the GET-parameter `foobar=1`.

First, create a service that will handle the parameter for your scenario

```php
<?php

namespace CL\Bundle\DemoBundle\EventListener;

use CL\Bundle\TriggerBundle\Spec\ParameterHandlerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

class AcmeParameterHandler extends ParameterHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function onFoo($value, RedirectHelper $redirectHelper)
    {
        if ($value === '1') {
            // do something awesome...
        }
    }
}
```

Now you just need to register this service (exact location depends on your setup):
```yml
# app/config/services.yml
services:
  app.parameter_handler:
    class: AppBundle\EventListener\AcmeParameterHandler
    tags:
      - { name: cl_trigger.parameter_handler, parameter: foobar, method: onFoo }
```

The `parameter` attribute refers to the name of the GET-parameter that will trigger your service.

The use of tags by this bundle allow you to register multiple handlers to handle different scenario's,
even for the same request.

For instance, you could have one service handle multiple parameters separately:
```yml
# app/config/services.yml
services:
  app.parameter_handler:
    class: AppBundle\EventListener\ParameterHandler
    tags:
      - { name: cl_trigger.parameter_handler, parameter: foo, method: onFoo }
      - { name: cl_trigger.parameter_handler, parameter: bar, method: onBar }
```

...or multiple services for different scenario's (gotta love separation of concerns!):
```yml
# app/config/services.yml
services:
  # perhaps a service that tracks number of incoming reseller referrals?
  app.parameter_handler.reseller_counter:
    class: AppBundle\EventListener\ResellerParameterHandler
    arguments:
      - @doctrine
    tags:
      - { name: cl_trigger.parameter_handler, parameter: reseller, method: onReseller }

  # perhaps a shop's trigger to store something in the session?
  app.parameter_handler.confirm:
    class: AppBundle\EventListener\ConfirmParameterHandler
    arguments:
      - @session
    tags:
      - { name: cl_trigger.parameter_handler, parameter: confirm, method: onConfirm }
```


### Handling a parameter and redirecting to an URL without the parameter

In the example above, we simply just did a check on the parameter's value, and then executed whatever business logic we wanted.

However, you might want this parameter to be removed from the URL directly after executing your logic; this is where the `RedirectHelper` comes in handy.

Let's review the `onFoo()`-method mentioned above again, only this time; we use the `RedirectHelper` to send a
redirect to the current request's URL; it automatically strips the parameter that triggered your service:

```php
<?php
/**
 * {@inheritdoc}
 */
public function onFoo($value, RedirectHelper $redirectHelper)
{
    if ($value === '1') {
        // do something awesome...
        $foo = 'bar'; // wow... that rocked!

        // now redirect the user without the parameter that triggered this service (clean URL, prevent repeat etc.)
        // in our case this would redirect the user from '/?foo=1' to just '/'
        return $redirectHelper->createWithoutParameter();
    }
}
```

The `RedirectResponse` that the helper creates is returned and then detected by Symfony's internal event dispatching,
which then immediately sends it back to the client (to continue browsing with a clean URL, preventing repeats etc.).

Although I personally needed this kind of redirect for almost all of my own scenario's, you may not need to redirect your user in your case.
You could just simply return nothing and the request will proceed (as seen in the first example).

Of course you can also go further and modify the response before returning it, or even choose to still include the same
parameter by just using the `create()` method, instead of the `createWithoutParameter()` method:
```php
<?php
/**
 * {@inheritdoc}
 */
public function onFoo($value, RedirectHelper $redirectHelper)
{
    if ($value === '1') {
        // do something awesome...
        $foo = 'bar'; // wow... that rocked!

        // adding a cookie before returning the response
        $response = $redirectHelper->createWithoutParameter();
        $response->setCookie(new Cookie('...'));

        return $response;
    }
}
```

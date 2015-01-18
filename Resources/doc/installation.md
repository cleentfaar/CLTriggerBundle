# Installation

## Step 1) Get the bundle

First you need to get a hold of this bundle. There are two ways to do this:

### Method a) Using composer

Add the following to your ``composer.json`` (see http://getcomposer.org/)

    "require" :  {
        "cleentfaar/trigger-bundle": "dev-master"
    }

**NOTE:** Once releases are available, you should replace `dev-master` with the version you want.


### Method b) Using submodules

Run the following commands to bring in the needed libraries as submodules.

```bash
git submodule add https://github.com/cleentfaar/TriggerBundle.git vendor/bundles/CL/TriggerBundle
```


## Step 2) Register the namespaces

If you installed the bundle by composer, use the created autoload.php  (jump to step 3).
Add the following two namespace entries to the `registerNamespaces` call in your autoloader:

``` php
<?php
// app/autoload.php
$loader->registerNamespaces(array(
    // ...
    'CL\Bundle\TriggerBundle' => __DIR__.'/../vendor/cleentfaar/trigger-bundle',
    // ...
));
```


## Step 3) Register the bundle

To start using the bundle, register it in your Kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    // ...
    $bundles = array(
        // ...
        new CL\Bundle\TriggerBundle\CLTriggerBundle(),
        // ...
    );
    // ...
}
```


# Ready?

Check out the [usage documentation](usage.md)!

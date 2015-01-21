<?php

namespace CL\Bundle\TriggerBundle\Tests\DependencyInjection;

use CL\Bundle\TriggerBundle\DependencyInjection\Configuration;
use Matthias\SymfonyConfigTest\PhpUnit\AbstractConfigurationTestCase;

class ConfigurationTest extends AbstractConfigurationTestCase
{
    public function testValuesAreValidIfNoValuesProvided()
    {
        $this->assertConfigurationIsValid(
            [
                [] // no values at all
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfiguration()
    {
        return new Configuration();
    }
}


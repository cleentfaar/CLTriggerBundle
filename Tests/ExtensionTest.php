<?php

namespace CL\Bundle\TriggerBundle\Tests;

use CL\Bundle\TriggerBundle\DependencyInjection\CLTriggerExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

class ExtensionTest extends AbstractExtensionTestCase
{
    /**
     * @test
     */
    public function testParameters()
    {
        $this->load(['foo' => 'bar']);

        $this->assertContainerBuilderHasParameter('apple', 'pear');
    }

    /**
     * {@inheritdoc}
     */
    protected function getContainerExtensions()
    {
        return array(
            new CLTriggerExtension()
        );
    }
}

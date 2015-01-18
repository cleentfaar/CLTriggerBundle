<?php

namespace CL\Bundle\TriggerBundle\Tests\Util;

use CL\Bundle\TriggerBundle\Util\RedirectHelper;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class RedirectHelperTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    public function testCreateFromRequest()
    {
        $expectedQuery = ['foo' => 'bar'];
        $request       = Request::create('/test', 'GET', $expectedQuery);
        $redirect      = RedirectHelper::createFromRequest($request, ['apple']);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $redirect);

        $actualQuery = $this->getQueryFromRedirect($redirect);

        $this->assertArrayHasKey('foo', $actualQuery);
        $this->assertEquals('bar', $actualQuery['foo']);
    }

    public function testCreateFromRequestWithoutParameters()
    {
        $expectedQuery = ['foo' => 'bar', 'apple' => 'pie'];
        $request       = Request::create('/test', 'GET', $expectedQuery);
        $redirect      = RedirectHelper::createFromRequest($request, ['apple']);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $redirect);

        $actualQuery = $this->getQueryFromRedirect($redirect);

        $this->assertArrayHasKey('foo', $actualQuery);
        $this->assertEquals('bar', $actualQuery['foo']);

        $this->assertArrayNotHasKey('apple', $actualQuery);
    }

    /**
     * @param RedirectResponse $redirectResponse
     *
     * @return array|null
     */
    private function getQueryFromRedirect(RedirectResponse $redirectResponse)
    {
        parse_str(parse_url($redirectResponse->getTargetUrl(), PHP_URL_QUERY), $actualQuery);

        return $actualQuery;
    }
}

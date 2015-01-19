<?php

namespace CL\Bundle\TriggerBundle\Tests\Util;

use CL\Bundle\TriggerBundle\Util\RedirectHelper;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class RedirectHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateFromRequest()
    {
        $redirect = $this->createRedirect(['foo' => 'bar']);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $redirect);

        $actualQuery = $this->getQueryFromRedirect($redirect);

        $this->assertArrayHasKey('foo', $actualQuery);
        $this->assertEquals('bar', $actualQuery['foo']);
    }

    public function testCreateFromRequestStripped()
    {
        $redirect    = $this->createRedirect(['foo' => 'bar', 'apple' => 'pie'], ['apple']);
        $actualQuery = $this->getQueryFromRedirect($redirect);

        $this->assertArrayHasKey('foo', $actualQuery);
        $this->assertEquals('bar', $actualQuery['foo']);

        $this->assertArrayNotHasKey('apple', $actualQuery);
    }

    /**
     * @param array $expectedQuery
     * @param array $withoutParameters
     *
     * @return RedirectResponse
     */
    private function createRedirect(array $expectedQuery, array $withoutParameters = [])
    {
        $request        = Request::create('/test', 'GET', $expectedQuery);
        $redirectHelper = new RedirectHelper($request, $withoutParameters);
        $redirect       = $redirectHelper->create();

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse', $redirect);

        return $redirect;
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

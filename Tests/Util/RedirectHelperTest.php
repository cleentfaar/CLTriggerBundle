<?php

namespace CL\Bundle\TriggerBundle\Tests\Util;

use CL\Bundle\TriggerBundle\Util\RedirectHelper;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class RedirectHelperTest extends \PHPUnit_Framework_TestCase
{
    private $expectedQuery = [
        'foo' => 'bar',
    ];

    public function testCreateFromRequestWithoutParameters()
    {
        $redirect    = $this->createRedirect([]);
        $actualQuery = $this->getQueryFromRedirect($redirect);

        $this->assertArrayHasKey('foo', $actualQuery);
        $this->assertEquals($this->expectedQuery['foo'], $actualQuery['foo']);
    }

    public function testCreateFromRequestWithParameters()
    {
        $redirect    = $this->createRedirect(['foo']);
        $actualQuery = $this->getQueryFromRedirect($redirect);

        $this->assertArrayNotHasKey('foo', $actualQuery);
    }

    /**
     * @param array $withoutParameters
     *
     * @return RedirectResponse
     */
    private function createRedirect(array $withoutParameters)
    {
        $request        = Request::create('/test', 'GET', $this->expectedQuery);
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

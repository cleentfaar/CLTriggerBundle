<?php

namespace CL\Bundle\TriggerBundle\Tests\Util;

use CL\Bundle\TriggerBundle\Tests\AbstractTestCase;
use CL\Bundle\TriggerBundle\Util\RedirectHelper;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RedirectHelperTest extends AbstractTestCase
{
    public function testCreate()
    {
        $redirect    = $this->createRedirect(['foo' => 'bar'], 'foo');
        $actualQuery = $this->getQueryFromRedirect($redirect);

        $this->assertArrayNotHasKey('foo', $actualQuery);
    }

    public function testCreateWithoutParameter()
    {
        $currentQuery = ['foo' => 'bar'];
        $redirect     = $this->createRedirect($currentQuery);
        $actualQuery  = $this->getQueryFromRedirect($redirect);

        $this->assertArrayHasKey('foo', $actualQuery);
        $this->assertEquals($currentQuery['foo'], $actualQuery['foo']);
    }

    public function testCreateFromRequest()
    {
        $redirectHelper = RedirectHelper::createFromRequest($this->createGetRequest());

        $this->assertInstanceOf('CL\Bundle\TriggerBundle\Util\RedirectHelper', $redirectHelper);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Parameter to strip must be either a non-empty string or null (strip nothing), got: array
     */
    public function testCreateFromRequestWithNonStringParameter()
    {
        $redirect    = $this->createRedirect(['foo' => 'bar'], ['nonstring']);
        $actualQuery = $this->getQueryFromRedirect($redirect);

        $this->assertArrayNotHasKey('foo', $actualQuery);
    }

    /**
     * @param array       $currentQuery
     * @param string|null $withoutParameter
     *
     * @return RedirectResponse
     */
    private function createRedirect(array $currentQuery, $withoutParameter = null)
    {
        $request        = $this->createGetRequest($currentQuery);
        $redirectHelper = RedirectHelper::createFromRequest($request, $withoutParameter);

        if ($withoutParameter !== null) {
            $redirect = $redirectHelper->createWithoutParameter();
        } else {
            $redirect = $redirectHelper->create();
        }

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

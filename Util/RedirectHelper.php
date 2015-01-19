<?php

namespace CL\Bundle\TriggerBundle\Util;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class RedirectHelper
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var array|null
     */
    private $parameters;

    /**
     * @param Request $request
     * @param array   $parameters
     */
    public function __construct(Request $request, array $parameters = [])
    {
        $this->request    = $request;
        $this->parameters = $parameters;
    }

    /**
     * @param bool $withoutParameters
     *
     * @return RedirectResponse
     */
    public function create($withoutParameters = true)
    {
        if ($withoutParameters === false) {
            $withoutParameters = [];
        } else {
            $withoutParameters = $this->parameters;
        }

        $redirect = new RedirectResponse($this->getUrl($this->request, $withoutParameters));

        return $redirect;
    }

    /**
     * @param Request $request
     * @param array   $stripParameters
     *
     * @return string
     */
    private function getUrl(Request $request, array $stripParameters = [])
    {
        $path   = ltrim($request->getPathInfo(), '/');
        $host   = $request->getHttpHost();
        $scheme = $request->getScheme();

        $queryBag = clone($request->query);

        foreach ($stripParameters as $parameter) {
            $queryBag->remove($parameter);
        }

        $query = http_build_query($queryBag->all());

        $redirectUrl = sprintf('%s://%s/%s?%s', $scheme, $host, $path, $query);
        $redirectUrl = rtrim($redirectUrl, '?');

        return $redirectUrl;
    }
}

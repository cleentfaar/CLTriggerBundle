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
    private $strippableParameters;

    /**
     * @param Request $request
     * @param array   $strippableParameters
     */
    public function __construct(Request $request, array $strippableParameters = [])
    {
        $this->request              = $request;
        $this->strippableParameters = $strippableParameters;
    }

    /**
     * @param bool $withoutParameters
     *
     * @return RedirectResponse
     */
    public function create($withoutParameters = true)
    {
        $redirect = new RedirectResponse($this->getUrl($withoutParameters));

        return $redirect;
    }

    /**
     * @param bool $withoutParameters
     *
     * @return string
     */
    private function getUrl($withoutParameters = true)
    {
        $path     = ltrim($this->request->getPathInfo(), '/');
        $host     = $this->request->getHttpHost();
        $scheme   = $this->request->getScheme();
        $queryBag = clone($this->request->query);

        if ($withoutParameters === true) {
            foreach ($this->strippableParameters as $parameter) {
                $queryBag->remove($parameter);
            }
        }

        $query       = http_build_query($queryBag->all());
        $redirectUrl = sprintf('%s://%s/%s?%s', $scheme, $host, $path, $query);
        $redirectUrl = rtrim($redirectUrl, '?');

        return $redirectUrl;
    }
}

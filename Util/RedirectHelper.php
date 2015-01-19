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
     * @var array
     */
    private $parametersToStrip;

    /**
     * @param Request $request
     * @param array   $parametersToStrip
     */
    public function __construct(Request $request, array $parametersToStrip = [])
    {
        $this->request           = $request;
        $this->parametersToStrip = $parametersToStrip;
    }

    /**
     * @param array|null $parametersToStrip The parameters to strip from the redirect URL,
     *                                      leave null to strip parameter(s) configured during construction
     *
     * @return RedirectResponse
     */
    public function create(array $parametersToStrip = null)
    {
        if ($parametersToStrip === null) {
            $parametersToStrip = $this->parametersToStrip;
        }

        $redirect = new RedirectResponse($this->getUrl($parametersToStrip));

        return $redirect;
    }

    /**
     * @param array $parametersToStrip
     *
     * @return string
     */
    private function getUrl(array $parametersToStrip = [])
    {
        $path     = ltrim($this->request->getPathInfo(), '/');
        $host     = $this->request->getHttpHost();
        $scheme   = $this->request->getScheme();
        $queryBag = clone($this->request->query);

        foreach ($parametersToStrip as $parameter) {
            $queryBag->remove($parameter);
        }

        $query       = http_build_query($queryBag->all());
        $redirectUrl = sprintf('%s://%s/%s?%s', $scheme, $host, $path, $query);
        $redirectUrl = rtrim($redirectUrl, '?');

        return $redirectUrl;
    }
}

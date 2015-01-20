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
     * @return RedirectResponse
     */
    public function create()
    {
        return new RedirectResponse($this->getUrl([]));
    }

    /**
     * @return RedirectResponse
     */
    public function createWithoutParameter()
    {
        return new RedirectResponse($this->getUrl($this->parametersToStrip));
    }

    /**
     * @param array $parametersToStrip
     *
     * @return string
     */
    private function getUrl(array $parametersToStrip)
    {
        $path          = ltrim($this->request->getPathInfo(), '/');
        $schemeAndHost = $this->request->getSchemeAndHttpHost();
        $queryString   = '';

        if ($this->request->isMethod('GET') && $this->request->query->count() > 0) {
            $query = $this->request->query->all();

            foreach ($parametersToStrip as $parameter) {
                unset($query[$parameter]);
            }

            $queryString = '?' . http_build_query($query);
        }

        $redirectUrl = sprintf('%s/%s%s', $schemeAndHost, $path, $queryString);

        return $redirectUrl;
    }
}

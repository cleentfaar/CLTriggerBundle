<?php

namespace CL\Bundle\TriggerBundle\Util;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractParameterHandler implements ParameterHandlerInterface
{
    /**
     * @var Request|null
     */
    protected $request;

    /**
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param array|null $withoutParameter
     *
     * @return RedirectResponse
     */
    protected function createRedirect(array $withoutParameter = [])
    {
        $redirect = new RedirectResponse($this->getUrl($withoutParameter));

        return $redirect;
    }

    /**
     * @param array $stripParameters
     *
     * @return string
     */
    protected function getUrl(array $stripParameters = [])
    {
        $path     = ltrim($this->request->getPathInfo(), '/');
        $host     = $this->request->getHttpHost();
        $scheme   = $this->request->getScheme();

        $queryBag = clone($this->request->query);

        foreach ($stripParameters as $parameter) {
            $queryBag->remove($parameter);
        }

        $query = http_build_query($queryBag->all());

        $redirectUrl = sprintf('%s://%s/%s?%s', $scheme, $host, $path, $query);
        $redirectUrl = rtrim($redirectUrl, '?');

        return $redirectUrl;
    }
}

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
     * @var string|null
     */
    private $parameterToStrip;

    /**
     * @param Request     $request
     * @param string|null $parameterToStrip
     */
    public function __construct(Request $request, $parameterToStrip = null)
    {
        if (!is_string($parameterToStrip) && !is_null($parameterToStrip)) {
            throw new \InvalidArgumentException('Parameter to strip must be either a string or null (strip nothing)');
        }

        $this->request          = $request;
        $this->parameterToStrip = $parameterToStrip;
    }

    /**
     * @return RedirectResponse
     */
    public function create()
    {
        return new RedirectResponse($this->getUrl());
    }

    /**
     * @return RedirectResponse
     */
    public function createWithoutParameter()
    {
        return new RedirectResponse($this->getUrl($this->parameterToStrip));
    }

    /**
     * @param string|null $parameterToStrip
     *
     * @return string
     */
    private function getUrl($parameterToStrip = null)
    {
        $pathAndQueryString = $this->request->getPathInfo();

        if ($this->request->isMethod('GET') && $this->request->query->count() > 0) {
            $query = $this->request->query->all();

            if ($parameterToStrip !== null) {
                unset($query[$parameterToStrip]);
            }

            $pathAndQueryString .= '?' . http_build_query($query);
        }

        return $this->request->getSchemeAndHttpHost() . $pathAndQueryString;
    }
}

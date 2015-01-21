<?php

namespace CL\Bundle\TriggerBundle\Util;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class RedirectHelper
{
    /**
     * @var string
     */
    private $scheme;

    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $path;

    /**
     * @var array
     */
    private $query;

    /**
     * @var string|null
     */
    private $parameterToStrip;

    /**
     * @param string      $scheme
     * @param string      $host
     * @param string      $path
     * @param array       $query
     * @param string|null $parameterToStrip
     */
    public function __construct($scheme, $host, $path, array $query, $parameterToStrip = null)
    {
        if (!is_string($parameterToStrip) && !is_null($parameterToStrip) && !empty($parameterToStrip)) {
            throw new \InvalidArgumentException(sprintf(
                'Parameter to strip must be either a non-empty string or null (strip nothing), got: %s',
                gettype($parameterToStrip)
            ));
        }

        $this->scheme           = $scheme;
        $this->host             = $host;
        $this->path             = $path;
        $this->query            = $query;
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
     * @param Request     $request
     * @param string|null $parameterToStrip
     *
     * @return RedirectHelper
     */
    public static function createFromRequest(Request $request, $parameterToStrip = null)
    {
        return new RedirectHelper(
            $request->getScheme(),
            $request->getHost(),
            $request->getPathInfo(),
            $request->query->all(),
            $parameterToStrip
        );
    }

    /**
     * @param string|null $parameterToStrip
     *
     * @return string
     */
    private function getUrl($parameterToStrip = null)
    {
        $pathAndQueryString = $this->path;
        $query              = $this->query;

        if (!empty($query)) {
            if ($parameterToStrip !== null) {
                unset($query[$parameterToStrip]);
            }

            $pathAndQueryString .= '?' . http_build_query($query);
        }

        return sprintf('%s://%s%s', $this->scheme, $this->host, $pathAndQueryString);
    }
}

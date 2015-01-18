<?php

namespace CL\Bundle\TriggerBundle\Util;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class RedirectHelper implements ParameterHandlerInterface
{
    /**
     * @param Request           $request
     * @param array|string|null $withoutParameters
     *
     * @return RedirectResponse
     */
    public static function createFromRequest(Request $request, $withoutParameters = null)
    {
        if ($withoutParameters === null) {
            $withoutParameters = [];
        } elseif (!is_array($withoutParameters)) {
            $withoutParameters = [$withoutParameters];
        }

        $redirect = new RedirectResponse(self::getUrl($request, $withoutParameters));

        return $redirect;
    }

    /**
     * @param Request $request
     * @param array   $stripParameters
     *
     * @return string
     */
    private static function getUrl(Request $request, array $stripParameters = [])
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

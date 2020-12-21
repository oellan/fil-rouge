<?php

namespace BadBox\Http;

use BadBox\Http\Response\JsonResponse;
use BadBox\Http\Response\RedirectResponse;
use BadBox\Http\Response\Response;

abstract class AbstractController
{

    protected static function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public static function createRedirectResponse(string $url): Response
    {
        return new RedirectResponse($url);
    }

    public static function createJsonResponse(object $object, int $responseCode): Response
    {
        return new JsonResponse($object, $responseCode);
    }
}

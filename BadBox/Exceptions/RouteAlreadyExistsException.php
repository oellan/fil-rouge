<?php

namespace BadBox\Exceptions;

class RouteAlreadyExistsException
    extends BadBoxException
{
    public function __construct($pathOrErrorCode)
    {
        parent::__construct(
            (
            is_int($pathOrErrorCode)
                ? "Error $pathOrErrorCode"
                : "Path $pathOrErrorCode"
            ) . " already has a route registered"
        );
    }
}

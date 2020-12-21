<?php

namespace BadBox\Exceptions;

class BadResponseFromControllerException
    extends BadBoxException
{
    /**
     * BadResponseFromControllerException constructor.
     * @param string|int $pathOrErrorCode
     */
    public function __construct($pathOrErrorCode)
    {
        parent::__construct(
            (
            is_int($pathOrErrorCode)
                ? "Error $pathOrErrorCode"
                : "Path $pathOrErrorCode"
            ) . ' didn\'t return a Response instance'
        );
    }
}

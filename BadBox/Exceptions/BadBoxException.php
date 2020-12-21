<?php

namespace BadBox\Exceptions;

use Exception;
use Throwable;

class BadBoxException
    extends Exception
{
    private int $httpErrorCode;

    public function __construct(string $message = "", int $httpErrorCode = 500)
    {
        parent::__construct($message);
        $this->httpErrorCode = $httpErrorCode;
    }

    public function getHttpErrorCode(): int
    {
        return $this->httpErrorCode;
    }
}

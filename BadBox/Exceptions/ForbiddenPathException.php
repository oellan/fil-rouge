<?php

namespace BadBox\Exceptions;

use BadBox\Http\ResponseCode;

class ForbiddenPathException
    extends BadBoxException
{
    public function __construct($message)
    {
        parent::__construct($message, ResponseCode::FORBIDDEN);
    }
}

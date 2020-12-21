<?php

namespace BadBox\Http\Response;

use BadBox\Exceptions\BadBoxException;

abstract class Response
{

    abstract public function getResponseCode(): int;

    /**
     * @throws BadBoxException
     */
    abstract public function render(): void;
}

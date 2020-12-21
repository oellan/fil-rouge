<?php

namespace BadBox\Exceptions;

class RouteNotFoundException
    extends BadBoxException
{
    public function __construct($path)
    {
        parent::__construct("Couldn't find route for path $path", 404);
    }
}

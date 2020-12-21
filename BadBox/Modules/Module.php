<?php

namespace BadBox\Modules;

abstract class Module
{

    abstract public static function getInstance(): self;
}

<?php

namespace BadBox\Exceptions;

class EntityNotFoundException
    extends BadBoxException
{
    public function __construct(string $className, array $criteria = [])
    {
        /** @noinspection JsonEncodingApiUsageInspection */
        $criteriaJson = json_encode($criteria);
        parent::__construct("Can't find entity $className with criteria $criteriaJson");
    }
}

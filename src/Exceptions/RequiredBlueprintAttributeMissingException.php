<?php

namespace Linksderisar\Clay\Exceptions;


use Throwable;

class RequiredBlueprintAttributeMissingException extends BlueprintException
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct('Required Blueprint Attribute missing' . PHP_EOL . $message, $code, $previous);
    }
}

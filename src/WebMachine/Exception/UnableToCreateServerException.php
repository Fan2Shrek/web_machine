<?php

namespace WebMachine\WebMachine\Exception;

class UnableToCreateServerException extends \RuntimeException
{
    public function __construct(string $message = 'Failed to create server', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

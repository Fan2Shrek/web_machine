<?php

declare(strict_types=1);

namespace WebMachine\Runner\Exception;

final class UnknowRunnerException extends \RuntimeException
{
    public function __construct(string $host)
    {
    }
}

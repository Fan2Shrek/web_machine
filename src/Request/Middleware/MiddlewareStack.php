<?php

namespace WebMachine\Request\Middleware;

final class MiddlewareStack
{
    private int $index = 0;

    /**
     * @param MiddlewareInterface[] $middlewares
     */
    public function __construct(private array $middlewares = [])
    {
    }

    public function next(): MiddlewareInterface
    {
        if (!isset($this->middlewares[$this->index])) {
            throw new \RuntimeException('No more middlewares to process');
        }

        return $this->middlewares[$this->index++];
    }

    public function reset(): void
    {
        $this->index = 0;
    }
}

<?php

namespace WebMachine\Request\Middleware;

/**
* @todo refactor
*/
final class MiddlewareStack
{
    private \Iterator $middlewares;

    public function __construct(
        iterable $middlewares = [],
    ) {
        $this->middlewares = $middlewares->getIterator();
    }

    public function next(): MiddlewareInterface
    {
        if ($this->isLast()) {
            throw new \RuntimeException('No more middleware to process.');
        }

        $this->middlewares->next();

        return $this->middlewares->current();
    }

    public function isLast(): bool
    {
        return !$this->middlewares->valid();
    }

    public function reset(): void
    {
        $this->middlewares->rewind();
    }

    public function current(): MiddlewareInterface
    {
        return $this->middlewares->current();
    }
}

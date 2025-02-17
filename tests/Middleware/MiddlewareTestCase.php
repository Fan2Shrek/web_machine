<?php

declare(strict_types=1);

namespace WebMachine\Tests\Middleware;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WebMachine\Request\Middleware\MiddlewareInterface;
use WebMachine\Request\Middleware\MiddlewareStack;
use WebMachine\Tests\RequestTestTrait;

abstract class MiddlewareTestCase extends TestCase
{
    use RequestTestTrait;

    protected MiddlewareInterface $middleware;
    // @var ?callable
    protected $nextCallable;

    protected function processRequest(Request $request): Response
    {
        return $this->getMiddleware()->process($request, new MiddlewareStack(
            new MiddlewareIterator(
                $this->getMiddleware(),
                $this->getNextMiddleware(),
            )
        ));
    }

    protected function getNextMiddleware(): callable
    {
        return $this->nextCallable ?? fn (Request $request) => new Response();
    }

    abstract protected function getMiddleware(): MiddlewareInterface;
}

class MiddlewareIterator implements \IteratorAggregate
{
    public function __construct(
        private MiddlewareInterface $middleware,
        private $nextMiddleware,
    ) {
    }

    public function getIterator(): \Traversable
    {
        yield $this->middleware;

        yield new class ($this->nextMiddleware) implements MiddlewareInterface {
            public function __construct(
                private $nextMiddleware,
            ) {
            } 

            public function process(Request $request, MiddlewareStack $stack): Response
            {
                return ($this->nextMiddleware)($request);
            }
        };
    }
}

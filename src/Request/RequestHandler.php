<?php

namespace WebMachine\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WebMachine\Request\Middleware\MiddlewareStack;

final class RequestHandler implements RequestHandlerInterface
{


    public function __construct(
        private array $middlewares = []
    ) {
    }

    public function handleRawRequest(string $request): string
    {
        $request = new Request([], [], [], [], [], [], $request);

        $response = $this->handleRequest($request);

        return $response->__toString();
    }

    public function handleRequest(Request $request): Response
    {
        $stack = new MiddlewareStack($this->middlewares);

        return $stack->next()->process($request);
    }
}

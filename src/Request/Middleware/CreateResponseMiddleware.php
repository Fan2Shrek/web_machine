<?php

namespace WebMachine\Request\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class CreateResponseMiddleware implements MiddlewareInterface
{
    public function process(Request $request): Response
    {
        return new Response('Hello World!');
    }
}

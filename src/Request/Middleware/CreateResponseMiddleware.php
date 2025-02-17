<?php

namespace WebMachine\Request\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class CreateResponseMiddleware implements MiddlewareInterface
{
    public function process(Request $request, MiddlewareStack $stack): Response
    {
        $response = $stack->next($request)->process($request, $stack);

        if (null === $response->getContent()) {
            $response = new Response('Default response');
        }

        return $response;
    }
}

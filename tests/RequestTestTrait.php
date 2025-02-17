<?php

declare(strict_types=1);

namespace WebMachine\Tests;

use Symfony\Component\HttpFoundation\Request;

trait RequestTestTrait
{
    protected function createRawRequest(string $uri = '/', string $method = 'GET', array $query = [], array $request = [], array $server = [], string $body = ''): string
    {
        $request = Request::create($uri, $method, $query, [], [], $server, $body);

        return $request->__toString();
    }

    protected function createRequest(string $uri = '/', string $method = 'GET', array $query = [], array $request = [], array $server = [], string $body = ''): Request
    {
        return new Request(content: $this->createRawRequest($uri, $method, $query, $request, $server, $body));
    }

    protected function createRealRequest(string $uri = '/', string $method = 'GET', array $query = [], array $request = [], array $server = [], string $body = ''): Request
    {
        return Request::create($uri, $method, $query, [], [], $server, $body);
    }
}


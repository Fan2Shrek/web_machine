<?php

declare(strict_types=1);

namespace WebMachine\Request\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @todo adapt full request from raw data
 */
final class AdaptRequestMiddleware implements MiddlewareInterface
{
    public function process(Request $request, MiddlewareStack $stack): Response
    {
        $content = $request->getContent();
        $lines = explode("\r\n", $content);

        [$method, $uri, $protocol] = explode(' ', array_shift($lines));
        $params = parse_url($uri, PHP_URL_QUERY);

        $server = [
            'REQUEST_URI' => $uri,
            'REQUEST_METHOD' => $method,
            'SERVER_PROTOCOL' => $protocol,
        ];

        $body = array_pop($lines);
        array_pop($lines); // body headers delimiter

        foreach ($lines as $line) {
            [$key, $value] = explode(': ', $line);
            $server['HTTP_' . strtoupper(str_replace('-', '_', $key))] = trim($value);
        }

        if (null !== $params) {
            $params = $this->convertParams($params);
        }

        $request->initialize(
            $params ?? [],
            [],
            [],
            [],
            [],
            $server,
            $body,
        );

        $request->setMethod($method);

        return $stack->next()->process($request, $stack);
    }

    private function convertParams(string $params): array
    {
        $params = explode('&', $params);
        $realParams = [];

        foreach ($params as $param) {
            [$key, $value] = explode('=', $param);
            $realParams[$key] = $value;
        }

        return $realParams;
    }
}

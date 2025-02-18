<?php

declare(strict_types=1);

namespace WebMachine\Tests\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WebMachine\Request\Middleware\MiddlewareInterface;
use WebMachine\Request\Middleware\RFCMiddleware;

final class RFCMiddlewareTest extends MiddlewareTestCase
{
    public function tearDown(): void
    {
        SpyRFCMiddleware::reset();
    }

    public function testProcessRequest(): void
    {
        $request = new Request();

        $this->processRequest($request);

        $this->assertEquals(SpyRFCMiddleware::$requestCount, 1);
    }

    public function testProcessResponse(): void
    {
        $request = new Request();

        $this->processRequest($request);

        $this->assertEquals(SpyRFCMiddleware::$responseCount, 1);
    }

    protected function getMiddleware(): MiddlewareInterface
    {
        return $this->middleware ??= new RFCMiddleware(
            [new SpyRFCMiddleware()],
            [new SpyRFCMiddleware()]
        );
    }
}

class SpyRFCMiddleware 
{
    public static int $requestCount = 0;
    public static int $responseCount = 0;

    public function processRequest(Request $request): Request
    {
        self::$requestCount++;

        return $request;
    }

    public function processResponse(Response $response): Response
    {
        self::$responseCount++;

        return $response;
    }

    public static function reset(): void
    {
        self::$requestCount = 0;
        self::$responseCount = 0;
    }
}

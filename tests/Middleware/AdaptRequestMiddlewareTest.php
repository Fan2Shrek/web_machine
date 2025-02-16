<?php

declare(strict_types=1);

namespace WebMachine\Tests\Middleware;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WebMachine\Request\Middleware\AdaptRequestMiddleware;
use WebMachine\Request\Middleware\MiddlewareInterface;
use WebMachine\Request\Middleware\MiddlewareStack;
use WebMachine\Tests\RequestTestTrait;

final class AdaptRequestMiddlewareTest extends TestCase
{
    use RequestTestTrait;

    private MiddlewareInterface $middleware;

    #[DataProvider('providePopulateMethod')]
    public function testPopulateMethod(string $method): void
    {
        $request = $this->createRequest(method: $method);

        $this->processRequest($request);

        self::assertSame($method, $request->getMethod());
    }

    public static function providePopulateMethod(): iterable
    {
        yield 'GET' => ['GET'];
        yield 'POST' => ['POST'];
        yield 'PUT' => ['PUT'];
        yield 'DELETE' => ['DELETE'];
        yield 'PATCH' => ['PATCH'];
        yield 'OPTIONS' => ['OPTIONS'];
        yield 'HEAD' => ['HEAD'];
        yield 'TRACE' => ['TRACE'];
    }

    #[DataProvider('providePopulateUri')]
    public function testPopulateUri(string $uri): void
    {
        $request = $this->createRequest($uri);

        $this->processRequest($request);

        self::assertSame($uri, $request->getRequestUri());
    }

    public static function providePopulateUri(): iterable
    {
        yield 'empty' => ['/'];
        yield 'with data' => ['/foo'];
        yield 'with data and param' => ['/foo?bar=baz'];
    }

    #[DataProvider('providePopulateGet')]
    public function testPopulateGet(array $get): void
    {
        $request = $this->createRequest(query: $get);

        $this->processRequest($request);

        self::assertSame($get, $request->query->all());
    }

    public static function providePopulateGet(): iterable
    {
        yield 'empty' => [[]];
        yield 'with data' => [['foo' => 'bar']];
    }

    #[DataProvider('providePopulateHeaders')]
    public function testPopulateHeaders(array $headers): void
    {
        $server = [];
        foreach ($headers as $key => $value) {
            $server['HTTP_'.$key] = [$value];
        }

        $request = $this->createRequest(server: $server);

        $this->processRequest($request);

        $requestHeaders = $request->headers->all();

        foreach ($headers as $key => $value) {
            self::assertArrayHasKey($key, $requestHeaders);
            self::assertSame($value, current($requestHeaders[$key]));
        }
    }

    public static function providePopulateHeaders(): iterable
    {
        yield 'with data' => [['foo' => 'bar']];
    }

    #[DataProvider('providePopulateBody')]
    public function testPopulateBody(string $body): void
    {
        $request = $this->createRequest(body: $body);

        $this->processRequest($request);

        self::assertSame($body, $request->getContent());
    }

    public static function providePopulateBody(): iterable
    {
        yield 'empty' => [''];
        yield 'with data' => ['Hello World!'];
    }

    protected function processRequest(Request $request): void
    {
        $this->getMiddleware()->process($request, new MiddlewareStack(new MiddlewareIterator($this->getMiddleware())));
    }

    protected function getMiddleware(): AdaptRequestMiddleware
    {
        return $this->middleware ??= new AdaptRequestMiddleware();
    }
}

class MiddlewareIterator implements \IteratorAggregate
{
    public function __construct(private MiddlewareInterface $middleware)
    {
    }

    public function getIterator(): \Traversable
    {
        yield $this->middleware;

        yield new class implements MiddlewareInterface {
            public function process(Request $request, MiddlewareStack $stack): Response
            {
                return new Response();
            }
        };
    }
}

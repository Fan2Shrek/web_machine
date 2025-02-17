<?php

declare(strict_types=1);

namespace WebMachine\Tests\Middleware;

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Response;
use WebMachine\Request\Middleware\CreateResponseMiddleware;
use WebMachine\Request\Middleware\MiddlewareInterface;

final class CreateReponseMiddlewareTest extends MiddlewareTestCase
{
    #[DataProvider('provideResponseContent')]
    public function testCreateResponse(string $expectedContent, string $responseContent): void
    {
        $this->nextCallable = fn () => new Response($responseContent);

        $response = $this->processRequest($this->createRequest());

        $this->assertSame($expectedContent, $response->getContent());
    }

    public static function provideResponseContent(): iterable
    {
        yield 'empty content' => ['', ''];
        yield 'content' => ['Hello World', 'Hello World'];
    }

    protected function getMiddleware(): MiddlewareInterface
    {
        return $this->middleware ??= new CreateResponseMiddleware();
    }
}

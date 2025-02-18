<?php

declare(strict_types=1);

namespace WebMachine\Tests\Middleware;

use Monolog\Level;
use Monolog\JsonSerializableDateTimeImmutable;
use Monolog\Logger;
use Symfony\Component\HttpFoundation\Response;
use WebMachine\Request\Middleware\LogMiddleware;
use WebMachine\Request\Middleware\MiddlewareInterface;

final class LogMiddlewareTest extends MiddlewareTestCase
{
    protected function tearDown(): void
    {
        MockLogger::$logs = [];
    }

    public function testLogRequestMiddleware(): void
    {
        $request = $this->createResquestFromUri('http://example.com/');
        $request->attributes->set('website_name', 'website1');

        $this->processRequest($request);

        $logs = MockLogger::$logs;
        self::assertCount(2, $logs);

        self::assertEquals($logs[0]['level'], Level::Info);
        self::assertEquals($logs[0]['message'], 'Request incoming');
        self::assertEquals($logs[0]['context'], [
            'method' => 'GET',
            'uri' => 'http://example.com/',
        ]);
    }

    public function testLogResponseMiddleware(): void
    {
        $request = $this->createResquestFromUri('http://example.com/');
        $request->attributes->set('website_name', 'website1');
        $this->nextCallable = static fn () => new Response('Smells like teen spirit', 200);

        $this->processRequest($request);

        $logs = MockLogger::$logs;
        self::assertCount(2, $logs);

        self::assertEquals($logs[1]['level'], Level::Info);
        self::assertEquals($logs[1]['message'], 'Response');
        self::assertEquals($logs[1]['context'], [
            'status' => 200,
        ]);
    }

    protected function getMiddleware(): MiddlewareInterface
    {
        return $this->middleware ??= new LogMiddleware([
            new MockLogger('website1'),
        ]);
    }
}

class MockLogger extends Logger
{
    public static array $logs = [];

    public function __construct(public string $name)
    {
        $this->name = $name;
    }

    public function addRecord(int|Level $level, string $message, array $context = [], ?JsonSerializableDateTimeImmutable $datetime = null): bool
    {
        self::$logs[] = [
            'level' => $level,
            'message' => $message,
            'context' => $context,
        ];

        return true;
    }
}

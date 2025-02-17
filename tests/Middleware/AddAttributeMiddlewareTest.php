<?php

declare(strict_types=1);

namespace WebMachine\Tests\Middleware;

use WebMachine\Config\Website;
use WebMachine\WebsiteGuesser;
use WebMachine\Request\Middleware\AddAttributeMiddleware;
use WebMachine\Request\Middleware\MiddlewareInterface;

final class AddAttributeMiddlewareTest extends MiddlewareTestCase
{
    public function testWebsiteNameAttribute(): void
    {
        MockWebsiteGuesser::withWebsite([
            new Website(
                'monster',
                'example.com',
                80,
                [],
            )
        ]);
        $request = $this->createRealRequest(server: [
            'HTTP_HOST' => 'example.com',
        ]);
        $this->processRequest($request);

        self::assertSame($request->attributes->get('website_name'), 'monster');
    }

    public function testWebsiteNameAttributeUnknown(): void
    {
        MockWebsiteGuesser::withWebsite([]);
        $request = $this->createRealRequest(server: [
            'HTTP_HOST' => 'example.com',
        ]);
        $this->processRequest($request);

        self::assertSame($request->attributes->get('website_name'), null);
    }

    protected function getMiddleware(): MiddlewareInterface
    {
        return $this->middleware ??= new AddAttributeMiddleware(MockWebsiteGuesser::getInstance());
    }
}

class MockWebsiteGuesser extends WebsiteGuesser
{
    public static parent $instance;

    public static function withWebsite(array $website): void
    {
        self::getInstance()->websites = $website;
    }

    public static function getInstance(): parent
    {
        return self::$instance ??= new parent();
    }
}


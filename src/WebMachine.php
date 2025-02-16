<?php

namespace WebMachine;

use WebMachine\Request\Middleware;
use WebMachine\Request\RequestHandler;
use WebMachine\WebMachine\Server;

final class WebMachine
{
    private static self $instance;

    private Server $server;

    public static function start(): never
    {
        self::$instance = new self();

        self::$instance->initialize();
    }

    private function initialize(): void
    {
        $this->server = new Server(new RequestHandler($this->getMiddleware()));
        $this->server->start();
    }

    private function getMiddleware(): array
    {
        return [
            new Middleware\CreateResponseMiddleware(),
        ];
    }
}

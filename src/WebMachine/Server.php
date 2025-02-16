<?php

namespace WebMachine\WebMachine;

use WebMachine\Request\RequestHandlerInterface;
use WebMachine\WebMachine\Exception\UnableToCreateServerException;

final class Server
{
    private \Socket $socket;
    private bool $running = false;

    public function __construct(
        private RequestHandlerInterface $requestHandler
    ) {}

    public function start(): never
    {
        if ($this->running) {
            throw new \RuntimeException('Server is already running');
        }

        $this->running = true;
        $this->init('localhost', 8080);

        register_shutdown_function(fn () => $this->stop());

        $this->run();
    }

    private function init(string $host, int $port): void
    {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($this->socket === false) {
            throw new \RuntimeException('Failed to create socket: ' . socket_strerror(socket_last_error()));
        }

        if (!socket_bind($this->socket, $host, $port)) {
            throw new UnableToCreateServerException('Failed to bind socket: ' . socket_strerror(socket_last_error()));
        }

        if (!socket_listen($this->socket)) {
            throw new UnableToCreateServerException('Failed to listen on socket: ' . socket_strerror(socket_last_error()));
        }

        socket_set_nonblock($this->socket);
        socket_set_option($this->socket, SOL_SOCKET, SO_REUSEADDR, 1);
    }

    private function run(): void
    {
        while (1) {
            $client = socket_accept($this->socket);
            if ($client === false) {
                continue;
            }

            $request = socket_read($client, 1024);
            if ($request === false) {
                continue;
            }

            $response = $this->requestHandler->handleRawRequest($request);

            socket_write($client, $response);
            socket_close($client);
        }
    }

    private function stop(): void
    {
        if (!$this->running) {
            throw new \RuntimeException('Server is not running');
        }

        socket_close($this->socket);
        $this->running = false;

        exit(1);
    }
}

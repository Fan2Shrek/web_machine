<?php

namespace WebMachine\WebMachine;

use WebMachine\Request\RequestHandlerInterface;
use WebMachine\WebMachine\Exception\UnableToCreateServerException;

final class Server
{
    private \Socket $socket;

    public function __construct(
        private RequestHandlerInterface $requestHandler
    ) {
    }

    public function start(): never
    {
        $this->init('localhost', 8081);
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
}

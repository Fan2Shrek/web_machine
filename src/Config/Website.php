<?php

declare(strict_types=1);

namespace WebMachine\Config;

final class Website
{
    public function __construct(
        private string $name,
        private string $host,
        private int $port,
        private array $config,
    ) {
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function get(string $key): mixed
    {
        return $this->config[$key] ?? null;
    }
}

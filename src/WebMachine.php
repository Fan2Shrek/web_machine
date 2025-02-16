<?php

namespace WebMachine;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use WebMachine\DependencyInjection\WebMachineExtension;
use WebMachine\Request\Middleware;
use WebMachine\Request\RequestHandler;
use WebMachine\Request\RequestHandlerInterface;
use WebMachine\WebMachine\Server;

final class WebMachine
{
    private static self $instance;

    private Server $server;
    private bool $isInitialized = false;
    private ContainerInterface $container;

    public static function start(): never
    {
        self::$instance = new self();

        self::$instance->run();
    }

    private function run(): void
    {
        if (!$this->isInitialized) {
            $this->initialize();
        }

        $this->server->start();
    }

    private function initialize(): void
    {
        $this->buildContainer();
        $this->server = new Server($this->container->get('webmachine.request_handler'));

        $this->isInitialized = true;
    }

    private function buildContainer(): void
    {
        $builder = new ContainerBuilder();
        $builder->registerExtension(new WebMachineExtension);
        $builder->loadFromExtension('web_machine', []);

        $builder->compile();

        $this->container = $builder;
    }
}

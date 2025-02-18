<?php

namespace WebMachine;

use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use WebMachine\DependencyInjection\Compiler\TagRFCPass;
use WebMachine\DependencyInjection\WebMachineExtension;
use WebMachine\WebMachine\Server;

final class WebMachine
{
    public const VERSION = '1.0.0-beta';
    public const VERSION_NAME = 'Sunny Beta';

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
        $builder->addCompilerPass(new TagRFCPass);
        $builder->loadFromExtension('web_machine', []);

        $builder->compile();

        $this->container = $builder;
    }
}

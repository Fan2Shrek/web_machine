<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use WebMachine\Request\RequestHandler;
use WebMachine\Request\RequestHandlerInterface;
use WebMachine\Runner\RunnerDelegator;
use WebMachine\Runner\RunnerInterface;
use WebMachine\WebsiteDelegator;

return static function(ContainerConfigurator $container) {
    $container->services()
        ->set('webmachine.runner.delagator', RunnerDelegator::class)
            ->args([
                tagged_iterator('webmachine.runner'),
            ])

        ->set('webmachine.website_delegator', WebsiteDelegator::class)
            ->args([
                service('webmachine.runner.delagator'),
                tagged_iterator('webmachine.website'),
            ])

        ->set('webmachine.request_handler', RequestHandler::class)
            ->args([
                tagged_iterator('webmachine.request_middleware'),
            ])
            ->public()

        ->alias(RequestHandlerInterface::class, 'webmachine.request_handler')
        ->alias(RunnerInterface::class, 'webmachine.runner.delagator')
        ->alias(WebsiteDelegator::class, 'webmachine.website_delegator')
    ;
};

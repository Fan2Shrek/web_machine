<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use WebMachine\WebsiteGuesser;
use WebMachine\Request\RequestHandler;
use WebMachine\Request\RequestHandlerInterface;
use WebMachine\Runner\RunnerDelegator;
use WebMachine\Runner\RunnerInterface;
use WebMachine\Runner\StaticRunner;
use WebMachine\WebsiteDelegator;

return static function(ContainerConfigurator $container) {
    $container->services()
        ->set('webmachine.runner.delagator', RunnerDelegator::class)
            ->args([
                tagged_iterator('webmachine.runner'),
            ])

        ->set('webmachine.website_guesser', WebsiteGuesser::class)
            ->args([
                tagged_iterator('webmachine.website'),
            ])

        ->set('webmachine.website_delegator', WebsiteDelegator::class)
            ->args([
                service('webmachine.runner.delagator'),
                service('webmachine.website_guesser'),
            ])

        ->set('webmachine.request_handler', RequestHandler::class)
            ->args([
                tagged_iterator('webmachine.request_middleware'),
            ])
            ->public()

        ->alias(RequestHandlerInterface::class, 'webmachine.request_handler')
        ->alias(RunnerInterface::class, 'webmachine.runner.delagator')
        ->alias(WebsiteDelegator::class, 'webmachine.website_delegator')

        ->set('webmachine.runner.static', StaticRunner::class)
            ->tag('webmachine.runner')
    ;
};

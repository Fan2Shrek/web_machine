<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use WebMachine\Request\Middleware\AdaptRequestMiddleware;
use WebMachine\Request\Middleware\AddAttributeMiddleware;
use WebMachine\Request\Middleware\CreateResponseMiddleware;
use WebMachine\Request\Middleware\LogMiddleware;
use WebMachine\Request\Middleware\WebsiteDelagatorMiddleware;

return static function(ContainerConfigurator $container) {
    $container->services()
        ->set('webmachine.request_middleware.adapt_request', AdaptRequestMiddleware::class)
            ->tag('webmachine.request_middleware', ['priority' => 500])

        ->set('webmachine.request_middleware.add_attribute', AddAttributeMiddleware::class)
            ->args([
                service('webmachine.website_guesser'),
            ])
            ->tag('webmachine.request_middleware', ['priority' => 300])

        ->set('webmachine.request_middleware.log', LogMiddleware::class)
            ->args([
                tagged_iterator('webmachine.logger'),
            ])
            ->tag('webmachine.request_middleware', ['priority' => 200])

        ->set('webmachine.request_middleware.create_response', CreateResponseMiddleware::class)
            ->tag('webmachine.request_middleware', ['priority' => 100])

        ->set('webmachine.request_middleware.website_delegator', WebsiteDelagatorMiddleware::class)
            ->args([
                service('webmachine.website_delegator'),
            ])
            ->tag('webmachine.request_middleware', ['priority' => -999])
    ;
};

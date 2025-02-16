<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use WebMachine\Request\Middleware\AdaptRequestMiddleware;
use WebMachine\Request\Middleware\CreateResponseMiddleware;
use WebMachine\Request\Middleware\WebsiteDelagatorMiddleware;

return static function(ContainerConfigurator $container) {
    $container->services()
        ->set('webmachine.request_middleware.create_response', CreateResponseMiddleware::class)
            ->tag('webmachine.request_middleware', ['priority' => 100])

        ->set('webmachine.request_middleware.website_delegator', WebsiteDelagatorMiddleware::class)
            ->args([
                service('webmachine.website_delegator'),
            ])
            ->tag('webmachine.request_middleware', ['priority' => 0])

        ->set('webmachine.request_middleware.adapt_request', AdaptRequestMiddleware::class)
            ->tag('webmachine.request_middleware', ['priority' => 50])
    ;
};

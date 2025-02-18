<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use WebMachine\Request\RFC\RFC6454;
use WebMachine\Request\RFC\RFC7231;

return static function(ContainerConfigurator $container) {
    $container->services()
        ->set('webmachine.rfc.rfc7231', RFC7231::class)
            ->tag('webmachine.rfc', ['priority' => 100])

        ->set('webmachine.rfc.rfc6454', RFC6454::class)
            ->tag('webmachine.rfc', ['priority' => 100])
    ;
};

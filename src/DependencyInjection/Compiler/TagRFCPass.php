<?php

declare(strict_types=1);

namespace WebMachine\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use WebMachine\Request\RFC\RequestRFCInterface;
use WebMachine\Request\RFC\ResponseRFCInterface;

final class TagRFCPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    public function process(ContainerBuilder $container): void
    {
        foreach ($this->findAndSortTaggedServices('webmachine.rfc', $container) as $rfc) {
            $rfc = $container->getDefinition((string) $rfc);

            $r = $container->getReflectionClass($rfc->getClass());

            if ($r->implementsInterface(RequestRFCInterface::class)) {
                $rfc->addTag('webmachine.request_rfc');
            }

            if ($r->implementsInterface(ResponseRFCInterface::class)) {
                $rfc->addTag('webmachine.response_rfc');
            }
        }
    }
}

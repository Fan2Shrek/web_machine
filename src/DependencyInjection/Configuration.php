<?php

declare(strict_types=1);

namespace WebMachine\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('web_machine');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->stringNode('website_folder')
                    ->defaultValue('tests') // @todo change
                ->end()
                ->booleanNode('log')
                    ->defaultTrue()
                ->end()
            ->end();

        return $treeBuilder;
    }
}

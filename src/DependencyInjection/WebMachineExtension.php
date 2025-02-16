<?php

declare(strict_types=1);

namespace WebMachine\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;
use WebMachine\Config\Website;

final class WebMachineExtension extends Extension
{
    private ContainerBuilder $container;

    public function load(array $configs, ContainerBuilder $container): void
    {
        $this->container = $container;

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('web_machine.website_folder', $config['website_folder']);

        $loader = new PhpFileLoader($container, new FileLocator(dirname(__DIR__, 2).'/Resources/config'));

        $loader->load('services.php');
        $loader->load('request_middleware.php');

        $this->parseWebsites($config['website_folder']);
    }

    private function parseWebsites(string $websiteFolder): void
    {
        $this->container->setParameter('web_machine.website_folder', $websiteFolder);

        $finder = new Finder();
        $websites = [];

        foreach ($finder->files()->in($websiteFolder)->name('*.yaml') as $file) {
            $config = Yaml::parse($file->getContents());

            $name = array_key_first($config);
            $config = $config[$name];

            $website = new Definition(Website::class, [$name, $config['host'], $config['port'], $config]);
            $website->addTag('webmachine.website');

            $websites['webmachine.website.'.$name] = $website;
        }
        $this->container->addDefinitions($websites);
    }
}

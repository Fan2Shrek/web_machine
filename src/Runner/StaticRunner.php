<?php

declare(strict_types=1);

namespace WebMachine\Runner;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WebMachine\Config\Website;

final class StaticRunner implements RunnerInterface
{
    public function run(Website $website, Request $request): Response
    {
        $fs = new Filesystem();
        $response = new Response();

        if (!$fs->exists($root = $website->get('root'))) {
            // @todo maybe log or throw an exception
            $response->setStatusCode(404);
            $response->setContent('Not Found');

            return $response;
        }

        $file = $root.$request->getPathInfo();
        $fs->touch($file);

        if (!$fs->exists($file)) {
            $response->setStatusCode(404);
            $response->setContent('Not Found');

            return $response;
        }

        $response->setContent(file_get_contents($file) ?? '');

        return $response;
    }

    public function supports(Website $website): bool
    {
        return true;
    }
}

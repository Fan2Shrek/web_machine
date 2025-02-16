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
            return $this->renderNotFound($response);
        }

        $file = $root.$request->getPathInfo();

        if ($fs->exists($file) && is_dir($file)) {
            $file = rtrim($file, '/').'/index.html';
        }

        if (!$fs->exists($file)) {
            return $this->renderNotFound($response);
        }

        $response->setContent(file_get_contents($file) ?? '');

        return $response;
    }

    public function supports(Website $website): bool
    {
        return true;
    }

    private function renderNotFound(Response $response): Response
    {
        $response->setStatusCode(404);
        $response->setContent(file_get_contents(__DIR__.'/../../Resources/page/404.html') ?? '');

        return $response;
    }
}

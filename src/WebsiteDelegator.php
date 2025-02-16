<?php

declare(strict_types=1);

namespace WebMachine;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WebMachine\Config\Website;
use WebMachine\Runner\RunnerInterface;

final class WebsiteDelegator
{
    public function __construct(
        private RunnerInterface $runner,
        private iterable $websites = [],
    ) {
    }

    public function handleRequest(Request $request): Response
    {
        foreach ($this->websites as $website) {
            if ($request->getHost() === $website->getHost()) {
                return $this->doHandleRequest($website, $request);
            }
        }

        // @todo maybe UnknowHostException
        return new Response('Not Found', 404);
    }

    private function doHandleRequest(Website $website, Request $request): Response
    {
        return $this->runner->run($website, $request);
    }
}

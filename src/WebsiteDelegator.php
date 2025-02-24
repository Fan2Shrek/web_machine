<?php

declare(strict_types=1);

namespace WebMachine;

use WebMachine\WebsiteGuesser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WebMachine\Config\Website;
use WebMachine\Runner\RunnerInterface;

final class WebsiteDelegator
{
    public function __construct(
        private RunnerInterface $runner,
        private WebsiteGuesser $websiteGuesser,
    ) {
    }

    public function handleRequest(Request $request): Response
    {
        $website = $this->websiteGuesser->guessWebsite($request);

        if (null !== $website) {
            return $this->doHandleRequest($website, $request);
        }

        // @todo maybe UnknowHostException
        return new Response('Not Found', 404);
    }

    private function doHandleRequest(Website $website, Request $request): Response
    {
        return $this->runner->run($website, $request);
    }
}

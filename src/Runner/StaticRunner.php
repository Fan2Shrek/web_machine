<?php

declare(strict_types=1);

namespace WebMachine\Runner;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WebMachine\Config\Website;

final class StaticRunner implements RunnerInterface
{
    public function run(Website $website, Request $request): Response
    {
        $response = new Response();

        $response->setContent('');

        return $response;
    }

    public function supports(Website $website): bool
    {
        return true;
    }
}

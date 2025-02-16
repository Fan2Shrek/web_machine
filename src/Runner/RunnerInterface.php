<?php

declare(strict_types=1);

namespace WebMachine\Runner;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WebMachine\Config\Website;

interface RunnerInterface
{
    public function run(Website $website, Request $request): Response;

    public function supports(Website $website): bool;
}

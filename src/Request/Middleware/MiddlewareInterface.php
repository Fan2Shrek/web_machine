<?php

declare(strict_types=1);

namespace WebMachine\Request\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface MiddlewareInterface
{
    public function process(Request $request): Response;
}

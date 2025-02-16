<?php

namespace WebMachine\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface RequestHandlerInterface
{
    public function handleRawRequest(string $request): string;

    public function handleRequest(Request $request): Response;
}

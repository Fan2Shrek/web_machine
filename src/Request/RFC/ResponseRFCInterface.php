<?php

declare(strict_types=1);

namespace WebMachine\Request\RFC;

use Symfony\Component\HttpFoundation\Response;

interface ResponseRFCInterface
{
    public function processResponse(Response $response): Response;
}

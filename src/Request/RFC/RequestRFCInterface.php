<?php

declare(strict_types=1);

namespace WebMachine\Request\RFC;

use Symfony\Component\HttpFoundation\Request;

interface RequestRFCInterface
{
    public function processRequest(Request $request): Request;
}

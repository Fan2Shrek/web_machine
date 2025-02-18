<?php

declare(strict_types=1);

namespace WebMachine\Request\RFC;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @see https://datatracker.ietf.org/doc/html/rfc6454
 */
final class RFC6454 implements RequestRFCInterface, ResponseRFCInterface
{
    private ?string $origin;

    public function processRequest(Request $request): Request
    {
        $this->origin = $request->headers->get('Origin');

        if ($this->origin !== null) {
            $request->headers->set('Access-Control-Allow-Origin', $this->origin);
        }

        return $request;
    }

    public function processResponse(Response $response): Response
    {
        if (!$response->headers->has('Access-Control-Allow-Origin') || '*' === $response->headers->get('Access-Control-Allow-Origin')) {
            return $response;
        }

        if ($this->origin === null || !$response->headers->has('Access-Control-Allow-Origin')) {
            $response->setStatusCode(401);

            return $response;
        }

        return $response;
    }
}

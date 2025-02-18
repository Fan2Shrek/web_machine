<?php

declare(strict_types=1);

namespace WebMachine\Request\RFC;

use Symfony\Component\HttpFoundation\Response;
use WebMachine\WebMachine;

/**
 * @see https://datatracker.ietf.org/doc/html/rfc7231
 *
 * @todo cookies ?
 */
final class RFC7231 implements ResponseRFCInterface
{
    public function processResponse(Response $response): Response
    {
        foreach ($this->getDefaultHeaders() as $name => $value) {
            if (!$response->headers->has($name)) {
                $response->headers->set($name, $value);
            }
        }

        if (299 <= $response->getStatusCode() && $response->getStatusCode() <= 399) {
            if (!$response->headers->has('Location')) {
                $response->headers->set('Location', '/');
            }
        }
        
        return $response;
    }

    private function getDefaultHeaders(): array
    {
        return [
            'Date' => date('D, d M Y H:i:s T'),
            'Content-Type' => 'text/html; charset=UTF-8',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Server' => 'webmachine/'.WebMachine::VERSION,
        ];
    }
}

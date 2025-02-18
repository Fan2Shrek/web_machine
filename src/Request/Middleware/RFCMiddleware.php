<?php

declare(strict_types=1);

namespace WebMachine\Request\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class RFCMiddleware implements MiddlewareInterface
{
    /**
     * @param iterable<RequestRFCInterface> $requestRFCProcessors
     * @param iterable<ResponseRFCInterface> $responseRFCProcessors
     */
    public function __construct(
        private iterable $requestRFCProcessors,
        private iterable $responseRFCProcessors,
    ) {
    }

    public function process(Request $request, MiddlewareStack $stack): Response
    {
        foreach ($this->requestRFCProcessors as $processor) {
            $request = $processor->processRequest($request);
        }

        $response = $stack->next()->process($request, $stack);

        foreach ($this->responseRFCProcessors as $processor) {
            $response = $processor->processResponse($response);
        }

        return $response;
    }
}

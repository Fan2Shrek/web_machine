<?php

declare(strict_types=1);

namespace WebMachine\Request\Middleware;

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class LogMiddleware implements MiddlewareInterface
{
    /**
     * @param iterable<Logger> $loggers
     */
    public function __construct(
        private iterable $loggers = [],
    ) {
    }

    public function process(Request $request, MiddlewareStack $stack): Response
    {
        $logger = $this->getLogger($request);

        if (null === $logger) {
            return $stack->next()->process($request, $stack);
        }

        $logger->info('Request incoming', [
            'method' => $request->getMethod(),
            'uri' => $request->getUri(),
        ]);

        $response = $stack->next()->process($request, $stack);

        $logger->info('Response', [
            'status' => $response->getStatusCode(),
        ]);

        return $response;
    }

    private function getLogger(Request $request): ?LoggerInterface
    {
        if ($request->attributes->has('website_name')) {
            $websiteName = $request->attributes->get('website_name');

            foreach ($this->loggers as $logger) {
                if ($logger->getName() === $websiteName) {
                    return $logger;
                }
            }
        }

        return null;
    }
}

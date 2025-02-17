<?php

declare(strict_types=1);

namespace WebMachine\Request\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WebMachine\WebsiteGuesser;

/**
 * Set attributes to request
 * to help other middlewares
 */
final class AddAttributeMiddleware implements MiddlewareInterface
{
    public function __construct(
        private WebsiteGuesser $websiteGuesser,
    ) {
    }

    public function process(Request $request, MiddlewareStack $stack): Response
    {
        $website = $this->websiteGuesser->guessWebsite($request);

        if (null !== $website) {
            $request->attributes->set('website_name', $website->getName());
        }

        return $stack->next()->process($request, $stack);
    }
}

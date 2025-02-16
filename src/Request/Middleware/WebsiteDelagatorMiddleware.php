<?php

declare(strict_types=1);

namespace WebMachine\Request\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WebMachine\WebsiteDelegator;

final class WebsiteDelagatorMiddleware implements MiddlewareInterface
{
    /**
     * @param Website[] $websites
     */
    public function __construct(
        private WebsiteDelegator $websiteDelegator,
    ) {
    }

    public function process(Request $request, MiddlewareStack $stack): Response
    {
        return $this->websiteDelegator->handleRequest($request);
    }
}

<?php

declare(strict_types=1);

namespace WebMachine;

use Symfony\Component\HttpFoundation\Request;
use WebMachine\Config\Website;

final class WebsiteGuesser
{
    /**
     * @param iterable<Website> $websites
     */
    public function __construct(
        private iterable $websites = [],
    ) {
    }

    public function guessWebsite(Request $request): ?Website
    {
        foreach ($this->websites as $website) {
            if ($request->getHost() === $website->getHost()) {
                return $website;
            }
        }

        return null;
    }
}

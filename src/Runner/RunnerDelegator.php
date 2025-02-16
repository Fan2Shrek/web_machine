<?php

declare(strict_types=1);

namespace WebMachine\Runner;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WebMachine\Config\Website;
use WebMachine\Runner\Exception\UnknowRunnerException;

final class RunnerDelegator implements RunnerInterface
{
    public function __construct(
        private iterable $runners = [],
    ) {
    }

    public function run(Website $website, Request $request): Response
    {
        foreach ($this->runners as $runner) {
            if ($runner->supports($website)) {
                return $runner->run($website, $request);
            }
        }

        throw new UnknowRunnerException(\sprintf('No runner found for website "%s" please check the configuration.', $website->getHost()));
    }

    public function supports(Website $website): bool
    {
        foreach ($this->runners as $runner) {
            if ($runner->supports($website)) {
                return true;
            }
        }

        return false;
    }
}

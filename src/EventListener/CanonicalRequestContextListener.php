<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RequestContext;

final class CanonicalRequestContextListener
{
    public function __construct(private RequestContext $requestContext, private string $baseUrl)
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $url = parse_url($this->baseUrl);

        //Forces Mercure url to https://localhost:8443
        $this->requestContext->setHost($url['host']);
        $this->requestContext->setScheme($url['scheme']);
        $this->requestContext->setHttpsPort($url['port']);
    }
}

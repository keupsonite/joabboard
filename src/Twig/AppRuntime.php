<?php

declare(strict_types=1);

namespace App\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\RuntimeExtensionInterface;

class AppRuntime implements RuntimeExtensionInterface
{
    private $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function activeOnSameRoute(string $expectedRoute)
    {
        return $this->request->get('_route') === $expectedRoute
            ? 'active'
            : ''
        ;
    }
}

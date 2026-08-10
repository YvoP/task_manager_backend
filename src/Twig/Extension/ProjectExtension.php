<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\ProjectExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ProjectExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('time_diff', [ProjectExtensionRuntime::class, 'getTimeDiff']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_projects', [ProjectExtensionRuntime::class, 'getProjects']),
            new TwigFunction('get_chats', [ProjectExtensionRuntime::class, 'getChats']),
            new TwigFunction('get_unstarted_chats', [ProjectExtensionRuntime::class, 'getUnstartedChats']),
        ];
    }
}

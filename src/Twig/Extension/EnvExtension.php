<?php

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EnvExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getEnv', function (string $name, $default = null) {
                return $_ENV[$name] ?? $default;
            }),
        ];
    }
}

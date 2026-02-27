<?php

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AssetFilter extends AbstractExtension
{
    private array $manifestList;

    private array $manifestListVue;

    public function __construct($manifestPath, $manifestPathVue)
    {
        $this->manifestList = $this->getManifestList($manifestPath);
        $this->manifestListVue = $this->getManifestList($manifestPathVue);
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('asset_filter', [$this, '__invoke']),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('assetPath', [$this, '__invoke']),
            new TwigFilter('assetPathVue', [$this, 'assetPathVue']),
        ];
    }

    public function __invoke($filename): string
    {
        return $this->assetPath($filename);
    }

    private function assetPath($filename): string
    {
        return $this->getFileNameByKey($filename);
    }

    private function getManifestList(string $path): array
    {
        if (file_exists($path)) {
            return json_decode(file_get_contents($path), true) ?? [];
        }

        return [];
    }

    private function getFileNameByKey(string $filename): string
    {
        if (array_key_exists($filename, $this->manifestList)) {
            return $this->manifestList[$filename];
        }

        return $filename;
    }

    public function assetPathVue(string $filename): string
    {
        return $this->manifestListVue[$filename] ?? $filename;
    }
}

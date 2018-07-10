<?php

namespace JeroenDesloovere\SitemapBundle\Generator;

use JeroenDesloovere\SitemapBundle\Provider\SitemapProviders;

class SitemapGenerator
{
    /** @var SitemapProviders */
    private $sitemapProviders;

    /** @var string */
    private $toPath;

    public function __construct(string $toPath, SitemapProviders $sitemapProviders)
    {
        $this->sitemapProviders = $sitemapProviders;
        $this->toPath = realpath($toPath);
    }

    public function generate(): void
    {
        foreach ($this->sitemapProviders as $sitemapProvider) {
            $this->generateSitemapForProvider($sitemapProvider);
        }
        $this->generateSitemapIndex(array_keys($this->sitemapProviders->getAll()));
    }

    private function generateSitemapForProvider(SitemapProvider $sitemapProvider): void
    {
        /** @var SitemapItem[] $items */
        $items = $sitemapProvider->getItems();
    }

    private function generateSitemapIndex(array $fileNames): void
    {
        // @todo: generate sitemap overview index
    }
}

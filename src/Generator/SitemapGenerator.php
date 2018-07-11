<?php

namespace JeroenDesloovere\SitemapBundle\Generator;

use JeroenDesloovere\SitemapBundle\Exception\SitemapException;
use JeroenDesloovere\SitemapBundle\Item\SitemapItem;
use JeroenDesloovere\SitemapBundle\Provider\SitemapProviderInterface;
use JeroenDesloovere\SitemapBundle\Provider\SitemapProviders;

class SitemapGenerator
{
    /** @var SitemapProviders */
    private $sitemapProviders;

    /** @var string - The path where we must save all the sitemaps.*/
    private $path;

    /**
     * @param string $path
     * @param SitemapProviders $sitemapProviders
     * @throws \Exception
     */
    public function __construct(string $path, SitemapProviders $sitemapProviders)
    {
        $this->sitemapProviders = $sitemapProviders;
        $this->setPath($path);
    }

    public function generate(): void
    {
        foreach ($this->sitemapProviders as $sitemapProvider) {
            $this->generateSitemapForProvider($sitemapProvider);
        }
        $this->generateSitemapIndex();
    }

    private function generateSitemapForProvider(SitemapProviderInterface $sitemapProvider): void
    {
        /** @var SitemapItem[] $items */
        $items = $sitemapProvider->getItems();

        // @todo: generate sitemap for provider
    }

    private function generateSitemapIndex(): void
    {
        $fileNames = array_keys($this->sitemapProviders->getAll());

        // @todo: generate sitemap overview index
    }

    public function regenerateForSitemapProvider(SitemapProviderInterface $sitemapProvider): void
    {
        $this->generateSitemapForProvider($sitemapProvider);
        $this->generateSitemapIndex();
    }

    /**
     * Set the path where whe must save all the sitemaps.
     *
     * @param string $path
     * @throws \Exception
     */
    public function setPath(string $path): void
    {
        $path = realpath($path);

        if ($path === '') {
            throw SitemapException::forEmptyPath();
        }

        $this->path = realpath($path);
    }
}

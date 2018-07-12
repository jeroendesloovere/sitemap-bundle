<?php

namespace JeroenDesloovere\SitemapBundle\Generator;

use JeroenDesloovere\SitemapBundle\Exception\SitemapException;
use JeroenDesloovere\SitemapBundle\Item\SitemapItem;
use JeroenDesloovere\SitemapBundle\Provider\SitemapProviderInterface;
use JeroenDesloovere\SitemapBundle\Provider\SitemapProviders;
use Symfony\Component\Routing\Generator\UrlGenerator;

class SitemapGenerator
{
    /** @var string */
    private $url;

    /** @var string - The path where we must save all the sitemaps.*/
    private $path;

    /** @var SitemapProviders */
    private $providers;

    /**
     * @param UrlGenerator $urlGenerator
     * @param string $path
     * @param SitemapProviders $providers
     * @throws \Exception
     */
    public function __construct(UrlGenerator $urlGenerator, string $path, SitemapProviders $providers)
    {
        $this->url = $urlGenerator->generate('', [], UrlGenerator::ABSOLUTE_URL);
        $this->providers = $providers;
        $this->setPath($path);
    }

    public function generate(): void
    {
        foreach ($this->providers->getAll() as $provider) {
            $this->saveSitemapForProvider($provider);
        }

        $this->saveSitemapIndex();
    }

    private function generateSitemapForProvider(SitemapProviderInterface $provider): string
    {
        $provider->createItems();
        $rootNode = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

        /** @var SitemapItem $item */
        foreach ($provider->getItems()->getAll() as $item) {
            $itemNode = $rootNode->addChild('url');
            $itemNode->addChild('loc', $this->url . $item->getUrl());
            $itemNode->addChild('changefreq', $item->getChangeFrequency()->__toString());
            $itemNode->addChild('lastmod', $item->getLastModifiedOn()->format('Y-m-d'));
            $itemNode->addChild('priority', $item->getPriority()/10);
        }

        return $rootNode->asXML();
    }

    private function generateSitemapIndex(): string
    {
        $rootNode = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></sitemapindex>');

        /** @var string $key */
        foreach ($this->providers->getKeys() as $key) {
            $itemNode = $rootNode->addChild('sitemap');
            $itemNode->addChild('loc', $this->url . '/sitemap_' . $key . '.xml');
            // @todo: lastmod
            $itemNode->addChild('lastmod', (new \DateTime())->format('Y-m-d'));
        }

        return $rootNode->asXML();
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function regenerateForSitemapProvider(SitemapProviderInterface $provider): void
    {
        $this->saveSitemapForProvider($provider);
        $this->saveSitemapIndex();
    }

    private function saveSitemapForProvider(SitemapProviderInterface $provider): void
    {
        file_put_contents(
            $this->getPath() . '/sitemap_' . $provider->getKey() . '.xml',
            $this->generateSitemapForProvider($provider)
        );
    }

    private function saveSitemapIndex(): void
    {
        file_put_contents(
            $this->getPath() . '/sitemap.xml',
            $this->generateSitemapIndex()
        );
    }

    /**
     * Set the path where whe must save all the sitemaps.
     *
     * @param string $path
     * @throws \Exception
     */
    public function setPath(string $path): void
    {
        if ($path === '') {
            throw SitemapException::forEmptyPath();
        }

        $this->path = $path;
    }
}

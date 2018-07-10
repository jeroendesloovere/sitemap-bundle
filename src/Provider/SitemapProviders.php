<?php

namespace JeroenDesloovere\SitemapBundle\Provider;

class SitemapProviders
{
    /** @var SitemapProviderInterface[] */
    private $sitemapProviders;

    public function add(SitemapProviderInterface $sitemapProvider): void
    {
        $this->sitemapProviders[$sitemapProvider->getKey()] = $sitemapProvider;
    }

    public function getAll(): array
    {
        return $this->sitemapProviders;
    }
}

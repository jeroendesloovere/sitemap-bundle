<?php

namespace JeroenDesloovere\SitemapBundle\Provider;

use JeroenDesloovere\SitemapBundle\Item\ChangeFrequency;
use JeroenDesloovere\SitemapBundle\Item\SitemapItems;

interface SitemapProviderInterface
{
    /**
     * @return string - Must return the wanted filename (without .xml), f.e.: 'sitemap_BlogArticle'
     */
    public function getFilename(): string;

    /**
     * @return string - Must return the Entity class name or a custom name, f.e: 'BlogArticle'
     */
    public function getKey(): string;

    public function getItems(): SitemapItems;

    public function createItem(string $url, \DateTime $lastModifiedOn, ChangeFrequency $changeFrequency): void;

    public function createItems(): void;
}

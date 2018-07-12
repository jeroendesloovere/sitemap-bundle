<?php

namespace JeroenDesloovere\SitemapBundle\Provider;

use JeroenDesloovere\SitemapBundle\Item\ChangeFrequency;
use JeroenDesloovere\SitemapBundle\Item\SitemapItem;
use JeroenDesloovere\SitemapBundle\Item\SitemapItems;

class SitemapProvider
{
    /** @var SitemapItems */
    private $items;

    /** @var string - bvb: NewsArticle, will create a "sitemap_NewsArticle.xml" file */
    private $key;

    public function __construct(string $key)
    {
        $this->key = $key;
        $this->items = new SitemapItems();
    }

    public function getItems(): SitemapItems
    {
        return $this->items;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function createItem(
        string $url,
        \DateTime $lastModifiedOn,
        ChangeFrequency $changeFrequency,
        int $priority = 5
    ): void {
        $this->items->add(new SitemapItem($url, $lastModifiedOn, $changeFrequency, $priority));
    }
}

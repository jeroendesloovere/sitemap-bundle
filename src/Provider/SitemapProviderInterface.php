<?php

namespace JeroenDesloovere\SitemapBundle\Provider;

use JeroenDesloovere\SitemapBundle\Item\ChangeFrequency;
use JeroenDesloovere\SitemapBundle\Item\SitemapItems;

interface SitemapProviderInterface
{
    public function getKey(): string;
    public function getItems(): SitemapItems;
    public function createItem(string $url, \DateTime $lastModifiedOn, ChangeFrequency $changeFrequency): void;
    public function createItems(): void;
}

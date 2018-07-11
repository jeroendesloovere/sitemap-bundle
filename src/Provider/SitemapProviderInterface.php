<?php

namespace JeroenDesloovere\SitemapBundle\Provider;

use JeroenDesloovere\SitemapBundle\Item\ChangeFrequency;

interface SitemapProviderInterface
{
    public function getKey(): string;
    public function createItem(string $url, \DateTime $editedOn, ChangeFrequency $changeFrequency): void;
    public function createItems(): void;
}

<?php

namespace JeroenDesloovere\SitemapBundle\Item;

class SitemapItems
{
    /** @var SitemapItem[] */
    private $items;

    public function add(SitemapItem $item): void
    {
        $this->items[] = $item;
    }

    public function getAll(): array
    {
        return $this->items;
    }
}

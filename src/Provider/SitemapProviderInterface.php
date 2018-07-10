<?php

namespace JeroenDesloovere\SitemapBundle\Provider;

interface SitemapProviderInterface
{
    public function getKey(): string;
    public function createItem(): void;
    public function createItems(): void;
}

<?php

namespace JeroenDesloovere\SitemapBundle\Exception;

class SitemapException extends \Exception
{
    public static function forEmptyPath(): self
    {
        return new self('The path you have given is empty.');
    }

    public static function forNotExistingChangeFrequency(string $changeFrequency): self
    {
        return new self('The given changeFrequency "' . $changeFrequency . '" does not exist.');
    }

    public static function forNotExistingSitemapProvider($sitemapProviderKey): self
    {
        return new self('The requested sitemap provider with key "' . $sitemapProviderKey . '" does not exist.');
    }
}

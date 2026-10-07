<?php

declare(strict_types=1);

namespace JeroenDesloovere\Tests\SitemapBundle\Provider;

use JeroenDesloovere\SitemapBundle\Exception\SitemapException;
use JeroenDesloovere\SitemapBundle\Provider\SitemapProvider;
use JeroenDesloovere\SitemapBundle\Provider\SitemapProviderInterface;
use JeroenDesloovere\SitemapBundle\Provider\SitemapProviders;
use PHPUnit\Framework\TestCase;

final class SitemapProvidersTest extends TestCase
{
    public function testAddExistsAndGet(): void
    {
        $providers = new SitemapProviders();
        $provider = new TestProvider('page');

        $providers->add($provider);

        $this->assertTrue($providers->exists('page'));
        $this->assertSame($provider, $providers->get('page'));
    }

    public function testGetKeys(): void
    {
        $providers = new SitemapProviders();
        $providers->add(new TestProvider('page'));
        $providers->add(new TestProvider('blog'));

        $this->assertSame(['page', 'blog'], $providers->getKeys());
    }

    public function testGetThrowsForUnknownProvider(): void
    {
        $this->expectException(SitemapException::class);
        $this->expectExceptionMessage('The requested sitemap provider with key "unknown" does not exist.');

        $providers = new SitemapProviders();
        $providers->get('unknown');
    }
}

final class TestProvider extends SitemapProvider implements SitemapProviderInterface
{
    public function createItems(): void
    {
    }
}

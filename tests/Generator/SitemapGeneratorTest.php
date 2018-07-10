<?php

declare(strict_types=1);

namespace JeroenDesloovere\Tests\SitemapBundle\Generator;

use JeroenDesloovere\SitemapBundle\Generator\SitemapGenerator;
use JeroenDesloovere\SitemapBundle\Item\ChangeFrequency;
use JeroenDesloovere\SitemapBundle\Provider\SitemapProvider;
use JeroenDesloovere\SitemapBundle\Provider\SitemapProviderInterface;
use JeroenDesloovere\SitemapBundle\Provider\SitemapProviders;
use PHPUnit\Framework\TestCase;

/**
 * How to execute all tests: `vendor/bin/phpunit tests`
 */
final class SitemapGeneratorTest extends TestCase
{
    /** @var SitemapProviders */
    private $providers;

    public function setUp(): void
    {
        $this->providers = new SitemapProviders();
        $this->providers->add(new TestBlogArticleSitemapProvider());
        $this->providers->add(new TestBlogCategorySitemapProvider());
    }

    public function testItems(): void
    {
        $this->assertEquals(2, count($this->providers->getAll()));
    }

    public function testGenerate(): void
    {
        $generator = new SitemapGenerator(__DIR__, $this->providers);

        // @todo: add test code
    }
}

class TestBlogArticleSitemapProvider extends SitemapProvider implements SitemapProviderInterface
{
    public function __construct()
    {
        parent::__construct('Test');
    }

    public function createItems(): void
    {
        foreach ($this->getDummyPages() as $page) {
            $this->createItem($page['url'], $page['editedOn'], ChangeFrequency::monthly());
        }
    }

    private function getDummyPages(): array
    {
        return [
            [
                'url' => '/nl/blog/article/my-first-blog-article',
                'editedOn' => new \DateTime(),
            ],
            [
                'url' => '/nl/blog/article/my-second-blog-article',
                'editedOn' => new \DateTime(),
            ]
        ];
    }
}

class TestBlogCategorySitemapProvider extends SitemapProvider implements SitemapProviderInterface
{
    public function __construct()
    {
        parent::__construct('Test');
    }

    public function createItems(): void
    {
        foreach ($this->getDummyPages() as $page) {
            $this->createItem($page['url'], $page['editedOn'], ChangeFrequency::monthly());
        }
    }

    private function getDummyPages(): array
    {
        return [
            [
                'url' => '/nl/blog/category/sitemap-generator',
                'editedOn' => new \DateTime(),
            ],
            [
                'url' => '/nl/blog/category/symfony-bundle',
                'editedOn' => new \DateTime(),
            ]
        ];
    }
}

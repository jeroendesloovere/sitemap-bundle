<?php

declare(strict_types=1);

namespace JeroenDesloovere\Tests\SitemapBundle\Generator;

use JeroenDesloovere\SitemapBundle\Exception\SitemapException;
use JeroenDesloovere\SitemapBundle\Generator\SitemapGenerator;
use JeroenDesloovere\SitemapBundle\Item\ChangeFrequency;
use JeroenDesloovere\SitemapBundle\Provider\SitemapProvider;
use JeroenDesloovere\SitemapBundle\Provider\SitemapProviderInterface;
use JeroenDesloovere\SitemapBundle\Provider\SitemapProviders;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing;

/**
 * How to execute all tests: `vendor/bin/phpunit tests`
 */
final class SitemapGeneratorTest extends TestCase
{
    /** @var SitemapProviders */
    private $providers;

    /** @var vfsStreamDirectory - We save the generated sitemaps to a virtual storage */
    private $virtualStorage;

    public function setUp(): void
    {
        $this->providers = new SitemapProviders();
        $this->providers->add(new TestPageSitemapProvider());
        $this->providers->add(new TestBlogArticleSitemapProvider());
        $this->providers->add(new TestBlogCategorySitemapProvider());
        $this->providers->add(new TestEmptySitemapProvider());
        $this->virtualStorage = vfsStream::setup();
    }

    public function testGenerate(): void
    {
        $router = $this->getMockBuilder(Router::class)
            ->disableOriginalConstructor()
            ->getMock();
        $router->method('getContext')->will(
            $this->returnValue($this->createMock(Routing\RequestContext::class))
        );

        $generator = new SitemapGenerator($router, __DIR__, $this->providers);

        // Overwrite the path to a virtual one for our tests
        $generator->setPath($this->virtualStorage->url());

        // Test if generate is working
        $generator->generate();
        $this->assertTrue($this->virtualStorage->hasChild('sitemap.xml'));
        $this->assertTrue($this->virtualStorage->hasChild('sitemap_Page.xml'));
        $this->assertTrue($this->virtualStorage->hasChild('sitemap_BlogArticle.xml'));
        $this->assertTrue($this->virtualStorage->hasChild('sitemap_BlogCategory.xml'));
    }

    public function testRegenerateForSitemapProvider(): void
    {
        $router = $this->getMockBuilder(Router::class)
            ->disableOriginalConstructor()
            ->getMock();
        $router->method('getContext')->will(
            $this->returnValue($this->createMock(Routing\RequestContext::class))
        );

        $generator = new SitemapGenerator($router, __DIR__, $this->providers);

        // Overwrite the path to a virtual one for our tests
        $generator->setPath($this->virtualStorage->url());

        // Regenerate only for the BlogArticle provider
        $generator->regenerateForSitemapProvider(new TestBlogArticleSitemapProvider());

        // The sitemap index must always be regenerated
        $this->assertTrue($this->virtualStorage->hasChild('sitemap.xml'));

        // Only the specific provider's sitemap file should be created
        $this->assertTrue($this->virtualStorage->hasChild('sitemap_BlogArticle.xml'));

        // Other providers' sitemap files should not be created
        $this->assertFalse($this->virtualStorage->hasChild('sitemap_Page.xml'));
        $this->assertFalse($this->virtualStorage->hasChild('sitemap_BlogCategory.xml'));
    }

    public function testItems(): void
    {
        $this->assertEquals(4, count($this->providers->getAll()));
    }

    public function testGenerateDoesNothingWithoutProviders(): void
    {
        $router = $this->getMockBuilder(Router::class)
            ->disableOriginalConstructor()
            ->getMock();
        $router->method('getContext')->willReturn(new Routing\RequestContext('', 'GET', 'example.com', 'https'));

        $generator = new SitemapGenerator($router, __DIR__, new SitemapProviders());
        $generator->setPath($this->virtualStorage->url());
        $generator->generate();

        $this->assertFalse($this->virtualStorage->hasChild('sitemap.xml'));
    }

    public function testGenerateWritesExpectedXmlContent(): void
    {
        $router = $this->getMockBuilder(Router::class)
            ->disableOriginalConstructor()
            ->getMock();
        $router->method('getContext')->willReturn(new Routing\RequestContext('', 'GET', 'example.com', 'https'));

        $providers = new SitemapProviders();
        $providers->add(new FixedTestSitemapProvider());

        $generator = new SitemapGenerator($router, __DIR__, $providers);
        $generator->setPath($this->virtualStorage->url());
        $generator->generate();

        $this->assertTrue($this->virtualStorage->hasChild('sitemap_Fixed.xml'));

        $xmlString = file_get_contents($this->virtualStorage->url() . '/sitemap_Fixed.xml');
        $this->assertNotFalse($xmlString);

        $xml = simplexml_load_string($xmlString);
        $this->assertInstanceOf(\SimpleXMLElement::class, $xml);

        $namespaces = $xml->getNamespaces(true);
        $this->assertArrayHasKey('', $namespaces);
        $urlNode = $xml->children($namespaces[''])->url[0];
        $this->assertNotNull($urlNode);
        $this->assertSame('https://example.com/fixed-page', (string) $urlNode->loc);
        $this->assertSame('monthly', (string) $urlNode->changefreq);
        $this->assertSame('2020-01-02', (string) $urlNode->lastmod);
        $this->assertSame('0.7', (string) $urlNode->priority);
    }

    public function testSetPathThrowsForEmptyPath(): void
    {
        $this->expectException(SitemapException::class);

        $router = $this->getMockBuilder(Router::class)
            ->disableOriginalConstructor()
            ->getMock();
        $router->method('getContext')->will(
            $this->returnValue($this->createMock(Routing\RequestContext::class))
        );

        $generator = new SitemapGenerator($router, __DIR__, $this->providers);
        $generator->setPath('');
    }
}

class TestBlogArticleSitemapProvider extends SitemapProvider implements SitemapProviderInterface
{
    public function __construct()
    {
        parent::__construct('BlogArticle');
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
        parent::__construct('BlogCategory');
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

class TestPageSitemapProvider extends SitemapProvider implements SitemapProviderInterface
{
    public function __construct()
    {
        parent::__construct('App\\PagesBundle\\Entity\\Page');
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
                'url' => '/nl/first-page',
                'editedOn' => new \DateTime(),
            ],
            [
                'url' => '/nl/second-page',
                'editedOn' => new \DateTime(),
            ]
        ];
    }
}

class TestEmptySitemapProvider extends SitemapProvider implements SitemapProviderInterface
{
    public function __construct()
    {
        parent::__construct('Empty');
    }

    public function createItems(): void
    {
        // no items
    }
}

class FixedTestSitemapProvider extends SitemapProvider implements SitemapProviderInterface
{
    public function __construct()
    {
        parent::__construct('Fixed');
    }

    public function createItems(): void
    {
        $this->createItem(
            '/fixed-page',
            new \DateTime('2020-01-02'),
            ChangeFrequency::monthly(),
            7
        );
    }
}

namespace App\PagesBundle\Entity;

class Page
{
    // Empty class for testing
}

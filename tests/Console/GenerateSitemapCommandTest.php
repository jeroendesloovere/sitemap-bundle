<?php

declare(strict_types=1);

namespace JeroenDesloovere\Tests\SitemapBundle\Console;

use JeroenDesloovere\SitemapBundle\Console\GenerateSitemapCommand;
use JeroenDesloovere\SitemapBundle\Generator\SitemapGenerator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

final class GenerateSitemapCommandTest extends TestCase
{
    public function testExecuteGeneratesSitemapAndReturnsSuccess(): void
    {
        $generator = $this->getMockBuilder(SitemapGenerator::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['generate'])
            ->getMock();
        $generator->expects($this->once())->method('generate');

        $command = new GenerateSitemapCommand($generator);
        $tester = new CommandTester($command);

        $statusCode = $tester->execute([]);

        $this->assertSame(Command::SUCCESS, $statusCode);
    }

    public function testCommandMetadata(): void
    {
        $generator = $this->getMockBuilder(SitemapGenerator::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['generate'])
            ->getMock();

        $command = new GenerateSitemapCommand($generator);

        $this->assertSame('sitemap:generate', $command->getName());
        $this->assertSame('Generate the sitemapindex and all the sitemaps', $command->getDescription());
    }
}

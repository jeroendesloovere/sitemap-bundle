<?php

namespace JeroenDesloovere\SitemapBundle\Console;

use JeroenDesloovere\SitemapBundle\Generator\SitemapGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Build sitemap
 * Example: "bin/console sitemap:generate"
 */
class GenerateSitemapCommand extends Command
{
    private $sitemapGenerator;

    public function __construct(SitemapGenerator $sitemapGenerator)
    {
        $this->sitemapGenerator = $sitemapGenerator;
        parent::__construct('sitemap:generate');
    }

    protected function configure(): void
    {
        $this->setDescription('Generate the sitemapindex and all the sitemaps');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->sitemapGenerator->generate();
    }
}

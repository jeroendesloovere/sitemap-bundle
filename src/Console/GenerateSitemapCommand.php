<?php

namespace JeroenDesloovere\SitemapBundle\Console;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Build sitemap
 * Example: "bin/console sitemap:generate"
 */
class GenerateSitemapCommand extends ContainerAwareCommand
{
    protected function configure(): void
    {
        $this
            ->setName('sitemap:generate')
            ->setDescription('Generate the sitemapindex and all the sitemaps');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->getContainer()->get('sitemap.generator')->generate();
    }
}

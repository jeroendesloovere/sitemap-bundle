<?php

namespace JeroenDesloovere\SitemapBundle;

use JeroenDesloovere\SitemapBundle\DependencyInjection\Compiler\SitemapProviderPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SitemapBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new SitemapProviderPass());
    }
}

<?php

namespace JeroenDesloovere\SitemapBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class SitemapProviderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('sitemap.providers')) {
            return;
        }

        $definition = $container->findDefinition('sitemap.providers');
        $taggedServices = $container->findTaggedServiceIds('sitemap.provider');

        foreach ($taggedServices as $id => $tags) {
            // add the SitemapProvider service to the SitemapProviders service
            $definition->addMethodCall('add', [
                new Reference($id),
            ]);
        }
    }
}

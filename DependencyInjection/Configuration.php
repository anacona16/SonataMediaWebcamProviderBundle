<?php

namespace Anacona16\Bundle\SonataMediaWebcamProviderBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sonata_media_webcam_provider', 'array');

        $rootNode
            ->children()
                ->integerNode('width')
                    ->defaultValue(320)
                    ->min(320)
                    ->max(1280)
                ->end()
            ->end()
            ->children()
                ->integerNode('height')
                    ->defaultValue(240)
                    ->min(240)
                    ->max(720)
                ->end()
            ->end()
            ->children()
                ->booleanNode('show_debug')
                    ->defaultFalse()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

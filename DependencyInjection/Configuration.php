<?php

namespace Otd\SuluEventBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('event');
        $rootNode = $treeBuilder->getRootNode();

        // You can add configuration options here if needed

        return $treeBuilder;
    }
}

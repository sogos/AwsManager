<?php

namespace Sogos\Bundle\DynamoDBBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
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
        $rootNode = $treeBuilder->root('sogos_dynamo_db');

        $rootNode
            ->children()
            ->scalarNode('database_name')->defaultValue('awsmanager')->end()
            ->scalarNode('region')->defaultValue('eu-west-1')->end()
            ->scalarNode('read_capacity_units')->defaultValue(20)->end()
            ->scalarNode('write_capacity_units')->defaultValue(20)->end()
            ->end();

        return $treeBuilder;
    }
}

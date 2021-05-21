<?php
/**
 * Created by PhpStorm.
 * User: carlosn
 * Date: 3/04/18
 * Time: 11:47 AM
 */

namespace ElasticSearch\Factory;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Psr\Container\ContainerInterface;

class ElasticSearchClientFactory
{
    /**
     * Factory ClientBuilder
     *
     * @param ContainerInterface $container
     * @return ClientBuilder
     */
    public function __invoke(ContainerInterface $container) : Client
    {
        $config = $container->get('config');

        $multiHandler = ClientBuilder::multiHandler();
        $client = ClientBuilder::create()
            ->setHosts($config['elasticSearch']['hosts'])
            ->setHandler($multiHandler)
            ->build();

        return $client;
    }
}
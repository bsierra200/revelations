<?php
/**
 * Created by PhpStorm.
 * User: carlosn
 * Date: 16/11/18
 * Time: 11:45 AM
 */

namespace BdiSearch\Factory;


use BdiSearch\Service\EsSearcherBdi;
use Psr\Container\ContainerInterface;

class EsSearcherBdiFactory
{
    /**
     * @param ContainerInterface $container
     * @return EsSearcherBdi
     */
    public function __invoke(ContainerInterface $container)
    {
        $elasticSearchClient = $container->get('ElasticSearch\Service\ElasticSearchClient');
        $esSearcher = new EsSearcherBdi();
        $esSearcher->setElasticSearchClient($elasticSearchClient);
        return $esSearcher;
    }
}
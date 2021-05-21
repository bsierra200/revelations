<?php
/**
 * Created by PhpStorm.
 * User: carlosn
 * Date: 16/11/18
 * Time: 11:45 AM
 */

namespace InternationalSearch\Factory;


use InternationalSearch\Service\EsSearcher;
use Psr\Container\ContainerInterface;

class EsSearcherFactory
{
    /**
     * @param ContainerInterface $container
     * @return EsSearcher
     */
    public function __invoke(ContainerInterface $container)
    {
        $elasticSearchClient = $container->get('ElasticSearch\Service\ElasticSearchClient');
        $esSearcher = new EsSearcher();
        $esSearcher->setElasticSearchClient($elasticSearchClient);
        return $esSearcher;
    }
}
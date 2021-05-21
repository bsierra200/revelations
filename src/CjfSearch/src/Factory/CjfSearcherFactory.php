<?php
/**
 * Created by PhpStorm.
 * User: carlosn
 * Date: 3/04/18
 * Time: 12:08 PM
 */

namespace CjfSearch\Factory;


use CjfSearch\Service\CjfSearcher;
use Psr\Container\ContainerInterface;

class CjfSearcherFactory
{

    /**
     * @param ContainerInterface $container
     * @return CjfSearcher
     */
    public function __invoke(ContainerInterface $container) : CjfSearcher
    {
        $elasticSearchClient = $container->get('ElasticSearch\Service\ElasticSearchClient');
        $penalSearcher = new CjfSearcher();
        $penalSearcher->setElasticSearchClient($elasticSearchClient);
        return $penalSearcher;
    }
}
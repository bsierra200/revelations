<?php
/**
 * Created by PhpStorm.
 * User: carlosn
 * Date: 3/04/18
 * Time: 12:08 PM
 */

namespace PenalSearch\Factory;

use PenalSearch\Service\PenalSearcher;
use Psr\Container\ContainerInterface;

class PenalSearcherFactory
{

    /**
     * @param ContainerInterface $container
     * @return PenalSearcher
     */
    public function __invoke(ContainerInterface $container) : PenalSearcher
    {
        $elasticSearchClient = $container->get('ElasticSearch\Service\ElasticSearchClient');
        $penalSearcher = new PenalSearcher();
        $penalSearcher->setElasticSearchClient($elasticSearchClient);
        return $penalSearcher;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: carlosn
 * Date: 28/03/19
 * Time: 11:20 AM
 */

namespace LaboralSearch\Factory;


use LaboralSearch\Handler\SearchActionHandler;
use Psr\Container\ContainerInterface;

class SearchActionHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return EsSearcher
     */
    public function __invoke(ContainerInterface $container)
    {
        $esSearcher = $container->get('LaboralSearch\Service\EsSearcher');
        $searchActionHandler = new SearchActionHandler($esSearcher);
        return $searchActionHandler;
    }
}
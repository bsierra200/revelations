<?php
/**
 * Created by PhpStorm.
 * User: carlosn
 * Date: 16/11/18
 * Time: 11:58 AM
 */

namespace InternationalSearch\Factory;


use InternationalSearch\Handler\SearchActionHandler;
use InternationalSearch\Service\EsSearcher;
use Psr\Container\ContainerInterface;

class SearchActionHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return EsSearcher
     */
    public function __invoke(ContainerInterface $container)
    {
        $esSearcher = $container->get(EsSearcher::class);
        $searchActionHandler = new SearchActionHandler($esSearcher);
        return $searchActionHandler;
    }
}
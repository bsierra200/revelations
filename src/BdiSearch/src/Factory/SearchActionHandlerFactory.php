<?php
/**
 * Created by PhpStorm.
 * User: carlosn
 * Date: 16/11/18
 * Time: 11:58 AM
 */

namespace BdiSearch\Factory;


use BdiSearch\Handler\SearchActionHandler;
use BdiSearch\Service\EsSearcherBdi;
use Psr\Container\ContainerInterface;

class SearchActionHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return EsSearcherBdi
     */
    public function __invoke(ContainerInterface $container)
    {
        if(isset($container->get("config")["bdi"])){
            $bdi = $container->get("config")["bdi"];
        }
        else
            $bdi = [];

        $esSearcher = $container->get(EsSearcherBdi::class);
        $searchActionHandler = new SearchActionHandler($esSearcher, $bdi);
        return $searchActionHandler;
    }
}
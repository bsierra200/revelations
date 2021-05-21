<?php
/**
 * Created by PhpStorm.
 * User: carlosn
 * Date: 10/05/18
 * Time: 04:25 PM
 */

namespace CjfSearch\Factory;


use CjfSearch\Handler\SearchActionHandler;
use CjfSearch\Service\CjfSearcher;
use Psr\Container\ContainerInterface;

class SearchActionHandlerFactory
{

    /**
     * @param ContainerInterface $container
     * @return SearchActionHandler
     */
    public function __invoke(ContainerInterface $container) : SearchActionHandler
    {
        $cjfSearch = $container->get(CjfSearcher::class);
        $searchActionHandler = new SearchActionHandler($cjfSearch);
        return $searchActionHandler;
    }

}
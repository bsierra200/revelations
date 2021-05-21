<?php


namespace CoreSearcher\Factory;


use CjfSearch\Service\CjfSearcher;
use CoreSearcher\Handler\DefaultActionHandler;
use PenalSearch\Handler\SearchActionHandler;
use PenalSearch\Service\PenalSearcher;
use Psr\Container\ContainerInterface;

class DefaultActionHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $penalSearcher=$container->get(SearchActionHandler::class);
        $cjfSearcher=$container->get(\CjfSearch\Handler\SearchActionHandler::class);
        $defaultActionHandler=new DefaultActionHandler($penalSearcher,$cjfSearcher);
        return $defaultActionHandler;
    }
}
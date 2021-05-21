<?php
namespace CoreSearcher\Factory;


use BdiSearch\Service\EsSearcherBdi;
use BuhoLegalSearch\Handler\BuhoLegalActionHandler;
use CoreSearcher\Handler\CombinedActionHandler;
use PenalSearch\Handler\SearchActionHandler;
use Psr\Container\ContainerInterface;

class CombinedActionHandlerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $penalSearcher=$container->get(SearchActionHandler::class);
        $cjfSearcher=$container->get(\CjfSearch\Handler\SearchActionHandler::class);
        $blSearcher=$container->get(BuhoLegalActionHandler::class);
        $esSearcher = $container->get(EsSearcherBdi::class);
        $CombinedActionHandler = new CombinedActionHandler($penalSearcher,$cjfSearcher,$blSearcher);
        $CombinedActionHandler->setEsSearcher($esSearcher);
        return $CombinedActionHandler;
    }
}
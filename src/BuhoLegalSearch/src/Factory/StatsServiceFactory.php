<?php


namespace BuhoLegalSearch\Factory;


use BuhoLegalSearch\Handler\BuhoLegalActionHandler;
use BuhoLegalSearch\Service\BuhoLegalClient;
use BuhoLegalSearch\Service\StatsService;
use Psr\Container\ContainerInterface;

class StatsServiceFactory
{
    /**
     * @param ContainerInterface $container
     * @return StatsService
     */
    public function __invoke(ContainerInterface $container) : StatsService
    {
        $statsService = new StatsService($container->get('doctrine.entity_manager.orm_logger'));
        return $statsService;
    }
}
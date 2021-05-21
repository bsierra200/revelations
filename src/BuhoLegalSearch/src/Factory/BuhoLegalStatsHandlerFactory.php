<?php


namespace BuhoLegalSearch\Factory;


use BuhoLegalSearch\Handler\BuhoLegalActionHandler;
use BuhoLegalSearch\Handler\BuhoLegalStatsHandler;
use BuhoLegalSearch\Service\BuhoLegalClient;
use BuhoLegalSearch\Service\StatsService;
use Psr\Container\ContainerInterface;

class BuhoLegalStatsHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return BuhoLegalStatsHandler
     */
    public function __invoke(ContainerInterface $container) : BuhoLegalStatsHandler
    {
        $statsService = $container->get(StatsService::class);
        $buhoLegalStats = new BuhoLegalStatsHandler($statsService);
        return $buhoLegalStats;
    }
}
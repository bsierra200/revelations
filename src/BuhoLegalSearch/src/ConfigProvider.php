<?php

declare(strict_types=1);

namespace BuhoLegalSearch;

use BuhoLegalSearch\Factory\BuhoLegalActionHandlerFactory;
use BuhoLegalSearch\Factory\BuhoLegalClientFactory;
use BuhoLegalSearch\Factory\BuhoLegalSearcherFactory;
use BuhoLegalSearch\Factory\BuhoLegalStatsHandlerFactory;
use BuhoLegalSearch\Factory\StatsServiceFactory;
use BuhoLegalSearch\Handler\BuhoLegalActionHandler;
use BuhoLegalSearch\Handler\BuhoLegalStatsHandler;
use BuhoLegalSearch\Service\BuhoLegalClient;
use BuhoLegalSearch\Service\BuhoLegalSearcher;
use BuhoLegalSearch\Service\StatsService;
use Laminas\ServiceManager\Factory\InvokableFactory;

/**
 * The configuration provider for the BuhoLegalSearch module
 *
 * @see https://docs.zendframework.com/zend-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates' => $this->getTemplates(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'invokables' => [
            ],
            'factories' => [
                BuhoLegalActionHandler::class => BuhoLegalActionHandlerFactory::class,
                BuhoLegalClient::class => BuhoLegalClientFactory::class,
                BuhoLegalStatsHandler::class => BuhoLegalStatsHandlerFactory::class,
                StatsService::class => StatsServiceFactory::class
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates(): array
    {
        return [
            'paths' => [
                'buholegalsearch' => [__DIR__ . '/../templates/'],
            ],
        ];
    }
}

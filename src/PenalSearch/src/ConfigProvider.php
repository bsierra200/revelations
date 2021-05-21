<?php

declare(strict_types=1);

namespace PenalSearch;
use PenalSearch\Factory\PenalSearcherFactory;
use PenalSearch\Factory\SearchActionHandlerFactory;
use PenalSearch\Handler\SearchActionHandler;
use PenalSearch\Service\PenalSearcher;

/**
 * The configuration provider for the PenalSearch module
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
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies() : array
    {
        return [
            'invokables' => [
            ],
            'factories'  => [
                SearchActionHandler::class => SearchActionHandlerFactory::class,
                PenalSearcher::class => PenalSearcherFactory::class
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates() : array
    {
        return [
            'paths' => [
                'penalsearch'    => [__DIR__ . '/../templates/'],
            ],
        ];
    }
}

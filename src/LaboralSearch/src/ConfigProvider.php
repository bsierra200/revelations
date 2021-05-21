<?php

declare(strict_types=1);

namespace LaboralSearch;

use InternationalSearch\Service\EsSearcher;
use LaboralSearch\Factory\SearchActionHandlerFactory;
use LaboralSearch\Handler\SearchActionHandler;

/**
 * The configuration provider for the LaboralSearch module
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
                SearchActionHandler::class => SearchActionHandlerFactory::class
            ],
            'aliases' => [
                'LaboralSearch\Service\EsSearcher' => EsSearcher::class
            ]
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates() : array
    {
        return [
            'paths' => [
                'laboralsearch'    => [__DIR__ . '/../templates/'],
            ],
        ];
    }
}

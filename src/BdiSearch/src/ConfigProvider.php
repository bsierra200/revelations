<?php

declare(strict_types=1);

namespace BdiSearch;
use BdiSearch\Factory\EsSearcherBdiFactory;
use BdiSearch\Factory\EsSearcherFactory;
use BdiSearch\Factory\SearchActionHandlerFactory;
use BdiSearch\Handler\SearchActionHandler;
use BdiSearch\Service\EsSearcher;
use BdiSearch\Service\EsSearcherBdi;

/**
 * The configuration provider for the BdiSearch module
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
                EsSearcherBdi::class => EsSearcherBdiFactory::class,
                SearchActionHandler::class => SearchActionHandlerFactory::class
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
                'bdisearch'    => [__DIR__ . '/../templates/'],
            ],
        ];
    }
}

<?php

declare(strict_types=1);

namespace InternationalSearch;
use InternationalSearch\Factory\EsSearcherFactory;
use InternationalSearch\Factory\SearchActionHandlerFactory;
use InternationalSearch\Handler\SearchActionHandler;
use InternationalSearch\Service\EsSearcher;

/**
 * The configuration provider for the InternationalSearch module
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
                EsSearcher::class => EsSearcherFactory::class,
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
                'internationalsearch'    => [__DIR__ . '/../templates/'],
            ],
        ];
    }
}

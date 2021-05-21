<?php

declare(strict_types=1);

namespace CjfSearch;
use CjfSearch\Factory\CjfSearcherFactory;
use CjfSearch\Factory\SearchActionHandlerFactory;
use CjfSearch\Handler\SearchActionHandler;
use CjfSearch\Service\CjfSearcher;

/**
 * The configuration provider for the CjfSearch module
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
                CjfSearcher::class =>CjfSearcherFactory::class,
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
                'cjfsearch'    => [__DIR__ . '/../templates/'],
            ],
        ];
    }
}

<?php

declare(strict_types=1);

namespace CoreSearcher;

use CoreSearcher\Factory\CombinedActionHandlerFactory;
use CoreSearcher\Factory\DefaultActionHandlerFactory;
use CoreSearcher\Handler\CombinedActionHandler;
use CoreSearcher\Handler\DefaultActionHandler;

/**
 * The configuration provider for the CoreSearcher module
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
                DefaultActionHandler::class => DefaultActionHandlerFactory::class,
                CombinedActionHandler::class => CombinedActionHandlerFactory::class
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
                'coresearcher'    => [__DIR__ . '/../templates/'],
            ],
        ];
    }
}

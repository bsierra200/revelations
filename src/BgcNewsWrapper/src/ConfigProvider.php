<?php

declare(strict_types=1);

namespace BgcNewsWrapper;
use BgcNewsWrapper\Factory\BgcNewsActionHandlerFactory;
use BgcNewsWrapper\Handler\BgcNewsActionHandler;
use BgcNewsWrapper\Service\BgcNewsClient;

/**
 * The configuration provider for the BgcNewsWrapper module
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
                BgcNewsClient::class => BgcNewsClient::class
            ],
            'factories'  => [
                BgcNewsActionHandler::class => BgcNewsActionHandlerFactory::class
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
                'bgcnewswrapper'    => [__DIR__ . '/../templates/'],
            ],
        ];
    }
}

<?php

declare(strict_types=1);

namespace Logger;

use Logger\Factory\BlErrorLoggerFactory;
use Logger\Factory\RequestLoggerMiddlewareFactory;
use Logger\Middleware\RequestLoggerMiddleware;
use Logger\Service\BlErrorLogger;
use Logger\Service\SimpleFileLogger;

/**
 * The configuration provider for the Logger module
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
                SimpleFileLogger::class => SimpleFileLogger::class
            ],
            'factories'  => [
                RequestLoggerMiddleware::class => RequestLoggerMiddlewareFactory::class,
                BlErrorLogger::class => BlErrorLoggerFactory::class
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
                'logger'    => [__DIR__ . '/../templates/'],
            ],
        ];
    }
}

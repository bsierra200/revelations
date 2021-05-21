<?php
namespace Logger\Factory;


use Logger\Middleware\RequestLoggerMiddleware;
use Logger\Service\BlErrorLogger;
use Psr\Container\ContainerInterface;

class BlErrorLoggerFactory
{
    /**
     * @param ContainerInterface $container
     * @return BlErrorLogger
     */
    public function __invoke(ContainerInterface $container)
    {
        $entityManager=$container->get('doctrine.entity_manager.orm_logger');
        $errorLogger = new BlErrorLogger($entityManager);
        return $errorLogger;
    }
}
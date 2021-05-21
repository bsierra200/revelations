<?php


namespace Logger\Factory;


use Doctrine\ORM\EntityManager;
use Logger\Middleware\RequestLoggerMiddleware;
use Psr\Container\ContainerInterface;

class RequestLoggerMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $entityManager=$container->get('doctrine.entity_manager.orm_logger');
        $requestLoggerMiddleware=new RequestLoggerMiddleware($entityManager);
        return $requestLoggerMiddleware;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: carlosn
 * Date: 10/05/18
 * Time: 04:25 PM
 */

namespace BuhoLegalSearch\Factory;


use BuhoLegalSearch\Handler\BuhoLegalActionHandler;
use BuhoLegalSearch\Service\BuhoLegalClient;
use Psr\Container\ContainerInterface;

class BuhoLegalActionHandlerFactory
{

    /**
     * @param ContainerInterface $container
     * @return BuhoLegalActionHandler
     */
    public function __invoke(ContainerInterface $container) : BuhoLegalActionHandler
    {
        $buhoLegalClient = $container->get(BuhoLegalClient::class);
        $entityManager = $container->get('doctrine.entity_manager.orm_logger');
        $buhoLegalActionHandler = new BuhoLegalActionHandler($buhoLegalClient,$entityManager);
        return $buhoLegalActionHandler;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: carlosn
 * Date: 26/07/18
 * Time: 10:35 AM
 */

namespace BgcNewsWrapper\Factory;


use BgcNewsWrapper\Handler\BgcNewsActionHandler;
use BgcNewsWrapper\Service\BgcNewsClient;
use Psr\Container\ContainerInterface;

class BgcNewsActionHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return BgcNewsActionHandler
     */
    public function __invoke(ContainerInterface $container) : BgcNewsActionHandler
    {
        $bgcNewsClient = $container->get(BgcNewsClient::class);
        $bgcNewsActionHandler = new BgcNewsActionHandler($bgcNewsClient);
        return $bgcNewsActionHandler;
    }
}
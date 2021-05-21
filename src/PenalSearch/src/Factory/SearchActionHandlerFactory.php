<?php
/**
 * Created by PhpStorm.
 * User: carlosn
 * Date: 3/04/18
 * Time: 12:08 PM
 */

namespace PenalSearch\Factory;


use PenalSearch\Handler\SearchActionHandler;
use PenalSearch\Service\PenalSearcher;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SearchActionHandlerFactory
{

    public function __invoke(ContainerInterface $container) : RequestHandlerInterface
    {
        $penalSearcher = $container->get(PenalSearcher::class);
        return new SearchActionHandler($penalSearcher);
    }
}
<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Mezzio\Application;
use Mezzio\MiddlewareFactory;

/**
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
 * $app->put('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.put');
 * $app->patch('/album/:id', App\Handler\AlbumUpdateHandler::class, 'album.patch');
 * $app->delete('/album/:id', App\Handler\AlbumDeleteHandler::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Handler\ContactHandler::class,
 *     Mezzio\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */
return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container) : void {
    $app->get('/', App\Handler\HomePageHandler::class, 'home');

    //specific search index routes to be deprecated
    $app->get('/api/ping', App\Handler\PingHandler::class, 'api.ping');

    $app->get('/api/bl/stats',\BuhoLegalSearch\Handler\BuhoLegalStatsHandler::class ,'blStats');


    $app->get('/api/penal[/{searchFlexibility:[a-z]+}]', [
        \Logger\Middleware\RequestLoggerMiddleware::class,
        \PenalSearch\Handler\SearchActionHandler::class
    ], 'api.penal');
    $app->get('/api/bl[/{searchFlexibility:[a-z]+}]', [
        \Logger\Middleware\RequestLoggerMiddleware::class,
        \BuhoLegalSearch\Handler\BuhoLegalActionHandler::class
    ], 'api.bl');
    $app->get('/api/cjf[/{searchFlexibility:[a-z]+}]', [
        \Logger\Middleware\RequestLoggerMiddleware::class,
        \CjfSearch\Handler\SearchActionHandler::class
    ], 'api.cjf');

    //process search in order - used in uber
    $app->route('/api[/{searchFlexibility:[a-z]+}]', [
        \Logger\Middleware\RequestLoggerMiddleware::class,
        \CoreSearcher\Handler\DefaultActionHandler::class,
        //\PenalSearch\Handler\SearchActionHandler::class,
        //\CjfSearch\Handler\SearchActionHandler::class,
    ], ['GET', 'POST'], 'api-default');

    //Combine results
    $app->route('/api-legal[/{searchFlexibility:[a-z]+}]', [
        \Logger\Middleware\RequestLoggerMiddleware::class,
        \CoreSearcher\Handler\CombinedActionHandler::class,
        //\PenalSearch\Handler\SearchActionHandler::class,
        //\CjfSearch\Handler\SearchActionHandler::class,
        //\BuhoLegalSearch\Handler\BuhoLegalActionHandler::class
    ], ['GET', 'POST'], 'api-legal');

    //wrapper for BGC News
    /*$app->route("/api-news[/]",[
        \Logger\Middleware\RequestLoggerMiddleware::class,
        \BgcNewsWrapper\Handler\BgcNewsActionHandler::class
    ],['GET','POST'],'api-news');*/

    //adding new route for international searches
    $app->route('/api-legal-international/{nation:[a-z]+}[/{searchFlexibility:[a-z]+}]', [
        \Logger\Middleware\RequestLoggerMiddleware::class,
        \InternationalSearch\Handler\SearchActionHandler::class
    ], ['GET', 'POST'], 'api-legal-international');

    //adding new route for international searches
    $app->route('/api-laboral[/{searchFlexibility:[a-z]+}]', [
        \Logger\Middleware\RequestLoggerMiddleware::class,
        \LaboralSearch\Handler\SearchActionHandler::class
    ], ['GET', 'POST'], 'api-laboral');

    //adding new route for live searches
    $app->route('/api-legal-live/{nation:[a-z]+}/{name:[A-Z\s]+}[/{city:[A-Z]+}]', [
        \Logger\Middleware\RequestLoggerMiddleware::class,
        \LiveSearch\Handler\SearchActionHandler::class
    ], ['GET', 'POST'], 'api-legal-live');

    //adding new route for international searches
    $app->route('/api-bdi[/{searchFlexibility:[a-z]+}]', [
        \Logger\Middleware\RequestLoggerMiddleware::class,
        \BdiSearch\Handler\SearchActionHandler::class
    ], ['GET', 'POST'], 'api-bdi');
};

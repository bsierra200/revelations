<?php
/**
 * Created by PhpStorm.
 * User: carlosn
 * Date: 26/07/18
 * Time: 09:07 AM
 */

namespace BgcNewsWrapper\Handler;


use BgcNewsWrapper\Service\BgcNewsClient;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;

class BgcNewsActionHandler implements RequestHandlerInterface
{

    /**
     * @var BgcNewsClient
     */
    private $bgcNewsClient;

    public function __construct(BgcNewsClient $bgcNewsClient)
    {
        $this->bgcNewsClient = $bgcNewsClient;
    }

    /**
     * Handle the request and return a response.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $params = $request->getQueryParams();
        $personData = ['name' => $params['name'], 'lastName' => $params['lastname'], 'secondLastName' => $params['secondLastName']];
        $results['results'] = $this->bgcNewsClient->bgcNewsSearch($personData);

        return new JsonResponse($results,200,[ 'Access-Control-Allow-Origin' => ['*']]);
    }
}
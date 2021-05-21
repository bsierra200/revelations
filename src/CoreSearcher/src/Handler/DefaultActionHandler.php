<?php
/**
 * Created by PhpStorm.
 * User: carlosn
 * Date: 15/05/18
 * Time: 12:27 PM
 */

namespace CoreSearcher\Handler;


use CjfSearch\Service\CjfSearcher;
use PenalSearch\Handler\SearchActionHandler;
use PenalSearch\Service\PenalSearcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;

class DefaultActionHandler implements MiddlewareInterface
{

    /**
     * @var SearchActionHandler
     */
    protected $penalSearcherHandler;

    /**
     * @var \CjfSearch\Handler\SearchActionHandler
     */
    protected $cjfSearcherHandler;

    /**
     * DefaultActionHandler constructor.
     * @param SearchActionHandler $penalSearcher
     * @param \CjfSearch\Handler\SearchActionHandler $cjfSearcher
     */
    public function __construct(SearchActionHandler $penalSearcher,\CjfSearch\Handler\SearchActionHandler $cjfSearcher)
    {
        $this->cjfSearcherHandler = $cjfSearcher;
        $this->penalSearcherHandler = $penalSearcher;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        //Calling penal
        $responsePenal = $this->penalSearcherHandler->doSearch($request);

        //Calling CJF
        $responseCjf = $this->cjfSearcherHandler->doSearch($request);

        //Merging Data
        $responseData = [];
        $penalData = json_decode($responsePenal->getBody()->getContents(), true);
        $cjfData = json_decode($responseCjf->getBody()->getContents(), true);
        $responseData['results'] = array_merge($penalData['results'], $cjfData['results']);
        $responseData['index'] = (count($cjfData['results']) > 0) ? "cjf" : "penal";
        $response = new JsonResponse($responseData);

        return $response;
    }
}
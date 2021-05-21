<?php
/**
 * Created by PhpStorm.
 * User: carlosn
 * Date: 25/07/18
 * Time: 05:03 PM
 */

namespace CoreSearcher\Handler;

use BdiSearch\Service\EsSearcherBdi;
use BuhoLegalSearch\Handler\BuhoLegalActionHandler;
use PenalSearch\Handler\SearchActionHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;

class CombinedActionHandler implements MiddlewareInterface
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
     * @var BuhoLegalActionHandler
     */
    protected $blSearcherHandler;

    /** @var  EsSearcherBdi */
    protected $esSearcher;

    /**
     * CombinedActionHandler constructor.
     * @param SearchActionHandler $penalSearcherHandler
     * @param \CjfSearch\Handler\SearchActionHandler $cjfSearcherHandler
     * @param BuhoLegalActionHandler $blSearcherHandler
     */
    public function __construct(SearchActionHandler $penalSearcherHandler,\CjfSearch\Handler\SearchActionHandler $cjfSearcherHandler,BuhoLegalActionHandler $blSearcherHandler)
    {
        $this->penalSearcherHandler=$penalSearcherHandler;
        $this->cjfSearcherHandler=$cjfSearcherHandler;
        $this->blSearcherHandler=$blSearcherHandler;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /*
        //Calling penal
        $responsePenal = $this->penalSearcherHandler->doSearch($request);

        //Calling CJF
        $responseCjf = $this->cjfSearcherHandler->doSearch($request);

        //Calling BL
        $responseBL = $this->blSearcherHandler->doSearch($request);

        //Merging Data
        $responseData = [];
        $penalData = json_decode($responsePenal->getBody()->getContents(), true);
        $cjfData = json_decode($responseCjf->getBody()->getContents(), true);
        $blData = json_decode($responseBL->getBody()->getContents(), true);
        $responseData['results']['revelations'] = array_merge($penalData['results'], $cjfData['results']);
        $responseData['results']['backup'] = $blData['results'];
        $response = new JsonResponse($responseData,200,[ 'Access-Control-Allow-Origin' => ['*']]);

        return $response;
        */

        $params = $request->getQueryParams();
        if (!isset($params['backwards']) || $params['backwards'] == "false") {
            $params['backwards'] = false;
        } else {
            $params['backwards'] = true;
        }

        $nation = $request->getAttribute("nation",NULL);
        $searchFlexibility = $request->getAttribute("searchFlexibility", "pipeline");

        $arrayIndex = [];

        foreach (["penal","cjf","judicial_estatal_mexico","laboral_mexico"] as $index) {
            //$this->setSearchIndex($index);
            $this->esSearcher->setIndexName($index);
            $this->indexName = $index;

            if ($searchFlexibility === "exact") {
                $results = $this->esSearcher->exactTextSearch($params['name'], $params['lastname'], $params['secondLastName'], $params['backwards'])['results'];
            } elseif ($searchFlexibility === "flexible") {
                $results = $this->esSearcher->partialFulltextSearch($params['name'], $params['lastname'], $params['secondLastName'])['results'];
            } else {
                $results = $this->esSearcher->exactTextSearch($params['name'], $params['lastname'], $params['secondLastName'], $params['backwards'])['results'];
                if (count($results['results']) == 0) {
                    $results = $this->esSearcher->partialFulltextSearch($params['name'], $params['lastname'], $params['secondLastName'])['results'];
                }
            }

            if (count($results) > 0) {
                $arrayIndex[] = $results;
            }
        }
        $resultNew = [];
        foreach ($arrayIndex as $key => $value) {
            foreach ($value as $key2 => $value2) {
                if (isset($value2['extra_field'])){
                    unset($value2['extra_field']);
                }
                $resultNew[] = $value2;
            }
        }

        //Calling BL
        $responseBL = $this->blSearcherHandler->doSearch($request);
        $responseData['results']['revelations'] = $resultNew;
        $blData = json_decode($responseBL->getBody()->getContents(), true);
        $responseData['results']['backup'] = $blData['results'];

        $response = new JsonResponse($responseData,200,[ 'Access-Control-Allow-Origin' => ['*']]);
        return $response;

    }

    /**
     * @return EsSearcherBdi
     */
    public function getEsSearcher(): EsSearcherBdi
    {
        return $this->esSearcher;
    }

    /**
     * @param EsSearcherBdi $esSearcher
     */
    public function setEsSearcher(EsSearcherBdi $esSearcher): void
    {
        $this->esSearcher = $esSearcher;
    }



}
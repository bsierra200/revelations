<?php
/**
 * Created by PhpStorm.
 * User: carlosn
 * Date: 28/03/19
 * Time: 11:11 AM
 */

namespace LaboralSearch\Handler;


use InternationalSearch\Service\EsSearcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;

class SearchActionHandler implements RequestHandlerInterface
{
    /** @var  EsSearcher */
    private $esSearcher;

    /**
     * SearchActionHandler constructor.
     * @param EsSearcher $esSearcher
     */
    public function __construct(EsSearcher $esSearcher)
    {
        $this->esSearcher = $esSearcher;
    }


    /**
     * Handle the request and return a response.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $params = $request->getQueryParams();
        if (!isset($params['backwards']) || $params['backwards'] == "false") {
            $params['backwards'] = false;
        } else {
            $params['backwards'] = true;
        }

        //setting the index search
        $this->esSearcher->setIndexName("laboral_mexico");

        $searchFlexibility = $request->getAttribute("searchFlexibility", "pipeline");
        if ($searchFlexibility === "exact") {
            $results = $this->esSearcher->exactTextSearch($params['name'], $params['lastname'], $params['secondLastName'], $params['backwards']);
        } elseif ($searchFlexibility === "flexible") {
            $results = $this->esSearcher->partialFulltextSearch($params['name'], $params['lastname'], $params['secondLastName']);
        } else {
            $results = $this->esSearcher->exactTextSearch($params['name'], $params['lastname'], $params['secondLastName'], $params['backwards']);
            if (count($results['results']) == 0) {
                $results = $this->esSearcher->partialFulltextSearch($params['name'], $params['lastname'], $params['secondLastName']);
            }
        }

        if (count($results) > 0) {
            $results['index'] = "laboral_mexico";
        }

        return new JsonResponse($results);
    }
}
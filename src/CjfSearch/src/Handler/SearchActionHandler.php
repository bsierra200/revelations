<?php
/**
 * Created by PhpStorm.
 * User: carlosn
 * Date: 16/05/18
 * Time: 09:18 PM
 */

namespace CjfSearch\Handler;


use CjfSearch\Service\CjfSearcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;

class SearchActionHandler  implements RequestHandlerInterface
{
    /**
     * @var CjfSearcher
     */
    private $cjfSearcher;

    public function __construct(CjfSearcher $cjfSearcher)
    {
        $this->cjfSearcher = $cjfSearcher;
    }


    /**
     * Handle the request and return a response.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->doSearch($request);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function doSearch(ServerRequestInterface $request): ResponseInterface
    {
        $params = $request->getQueryParams();

        if (!isset($params['backwards']) || $params['backwards'] == "false") {
            $params['backwards'] = false;
        } else {
            $params['backwards'] = true;
        }

        $searchFlexibility = $request->getAttribute("searchFlexibility", "pipeline");
        if ($searchFlexibility === "exact") {
            //$results = $this->cjfSearcher->partialFulltextSearch($params['name'], $params['lastname'], $params['secondLastName']);
            $results = $this->cjfSearcher->exactTextSearch($params['name'], $params['lastname'], $params['secondLastName'], $params['backwards']);
            /*if (count($results['results']) == 0) {
                $results = $this->cjfSearcher->partialFulltextSearch($params['name'], $params['lastname'], $params['secondLastName']);
            }*/
        } elseif ($searchFlexibility === "flexible") {
            $results = $this->cjfSearcher->partialFulltextSearch($params['name'], $params['lastname'], $params['secondLastName']);
        } else {
            $results = $this->cjfSearcher->exactTextSearch($params['name'], $params['lastname'], $params['secondLastName'], $params['backwards']);
            if (count($results['results']) == 0) {
                $results = $this->cjfSearcher->partialFulltextSearch($params['name'], $params['lastname'], $params['secondLastName']);
            }
        }

        if (count($results) > 0) {
            $results['index'] = "cjf";
        }

        return new JsonResponse($results);
    }
}
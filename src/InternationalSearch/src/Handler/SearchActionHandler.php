<?php
/**
 * Created by PhpStorm.
 * User: carlosn
 * Date: 16/11/18
 * Time: 11:52 AM
 */

namespace InternationalSearch\Handler;


use InternationalSearch\Service\EsSearcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;

class SearchActionHandler implements RequestHandlerInterface
{
    /** @var  EsSearcher */
    protected $esSearcher;

    /**
     * @var string
     */
    private $indexName;

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

        $nation = $request->getAttribute("nation",NULL);
        $this->setSearchIndex($nation);

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
            $results['index'] = $this->indexName;
        }

        return new JsonResponse($results);
    }

    /**
     * set the index within ES will search
     * @param $nation
     */
    public function setSearchIndex($nation)
    {
        switch ($nation) {
            case "honduras": {
                $this->indexName = "pj_hounduras";
                $this->esSearcher->setIndexName("pj_hounduras");
                break;
            }
            default: {
                $this->indexName = "pj_hounduras";
                $this->esSearcher->setIndexName("pj_hounduras");
                break;
            }
        }
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: carlosn
 * Date: 16/11/18
 * Time: 11:52 AM
 */

namespace BdiSearch\Handler;


use BdiSearch\Service\EsSearcher;
use BdiSearch\Service\EsSearcherBdi;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;

class SearchActionHandler implements RequestHandlerInterface
{
    /** @var  EsSearcherBdi */
    protected $esSearcher;

    /**
     * @var string
     */
    private $indexName;

    /**
     * @var string
     */
    protected $bdi;

    /**
     * SearchActionHandler constructor.
     * @param EsSearcherBdi $esSearcher
     */
    public function __construct(EsSearcherBdi $esSearcher, $bdi)
    {
        $this->esSearcher = $esSearcher;
        $this->bdi = $bdi;
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
        $searchFlexibility = $request->getAttribute("searchFlexibility", "pipeline");

        $arrayIndex = [];

        foreach ($this->bdi as $index) {
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

          return new JsonResponse($resultNew);
    }


    /**
     * @return mixed
     */
    public function getBdi()
    {
        return $this->bdi;
    }

    /**
     * @param mixed $bdi
     */
    public function setBdi($bdi): void
    {
        $this->bdi = $bdi;
    }



}
<?php
/**
 * Created by PhpStorm.
 * User: carlosn
 * Date: 2/04/18
 * Time: 10:53 AM
 */

namespace PenalSearch\Handler;

use PenalSearch\Service\PenalSearcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;

class SearchActionHandler implements RequestHandlerInterface
{
    /**
     * @var PenalSearcher
     */
    private $penalSearcher;

    /**
     * SearchActionHandler constructor.
     *
     * @param PenalSearcher $penalSearcher
     */
    public function __construct(PenalSearcher $penalSearcher)
    {
        $this->penalSearcher = $penalSearcher;
    }

    /**
     * Handle the request and return a response.
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->doSearch($request);
    }

    /**
     * Put the code in this method to call it apart
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function doSearch(ServerRequestInterface $request): ResponseInterface
    {
        $params=$request->getQueryParams();

        if(!isset($params['backwards']) || $params['backwards']=="false") {
            $params['backwards']=false;
        } else {
            $params['backwards']=true;
        }

        $searchFlexibility=$request->getAttribute("searchFlexibility","pipeline");
        if($searchFlexibility==="exact") {
            $results=$this->penalSearcher->exactTextSearch($params['name'],$params['lastname'],$params['secondLastName'],$params['backwards']);
        } elseif ($searchFlexibility==="flexible") {
            $results=$this->penalSearcher->partialFulltextSearch($params['name'],$params['lastname'],$params['secondLastName']);
        } else {
            $results=$this->penalSearcher->exactTextSearch($params['name'],$params['lastname'],$params['secondLastName'],$params['backwards']);
            if(count($results['results'])==0) {
                $results=$this->penalSearcher->fulltextSearch($params['name'],$params['lastname'],$params['secondLastName']);
            }
        }

        return new JsonResponse($results);
    }
}
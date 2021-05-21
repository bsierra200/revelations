<?php
/**
 * Created by PhpStorm.
 * User: carlosn
 * Date: 10/05/18
 * Time: 04:13 PM
 */

namespace BuhoLegalSearch\Handler;


use BuhoLegalSearch\Service\BuhoLegalClient;
use BuhoLegalSearch\Service\BuhoLegalSearcher;
use Doctrine\ORM\EntityManager;
use Logger\Entity\BuhoLegalLog;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;

class BuhoLegalActionHandler implements RequestHandlerInterface
{
    /**
     * @var BuhoLegalClient
     */
    private $buhoLegalClient;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(BuhoLegalClient $buhoLegalClient,EntityManager $entityManager)
    {
        $this->buhoLegalClient=$buhoLegalClient;
        $this->entityManager=$entityManager;
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
    public function doSearch(ServerRequestInterface $request):ResponseInterface
    {
        $params=$request->getQueryParams();
        $searchFlexibility=$request->getAttribute("searchFlexibility","pipeline");

        if($searchFlexibility==="exact") {
            $searchMode="exacto";
        } elseif ($searchFlexibility==="flexible") {
            $searchMode="aproximado";
        } else {
            $searchMode="sinorden";
        }

        $personData=['name'=>$params['name'],'lastName'=>$params['lastname'],'secondLastName'=>$params['secondLastName']];
        $results['results']=$this->buhoLegalClient->buhoLegalSearch($personData,$searchMode);

        $this->log($results,$request);

        if (count($results) > 0) {
            $results['index'] = "bl";
        }
        return new JsonResponse($results);
    }

    /**
     * @param array $results
     * @param ServerRequestInterface $request
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function log(array $results,ServerRequestInterface $request)
    {
        if(isset($results['statusCode'])) {
            unset($results['statusCode']);
            $status=$results['statusCode'];
        } else {
            $status=200;
        }
        if (isset($results['bodyString'])) {
            unset($results['bodyString']);
            $extra=$results['bodyString'];
        } else {
            $extra=NULL;
        }

        $params=$request->getQueryParams();
        $remoteIp=$request->getServerParams()['REMOTE_ADDR'] ?? NULL;
        $requestOrigin=$request->getHeaderLine('Request-Origin') ?? "";
        $requestUser=$request->getHeaderLine('Request-User') ?? "";
        $name=$params['name'] ?? "";
        $lastname=$params['lastname'] ?? "";
        $secondLastName=$params['secondLastName'] ?? "";
        $fullName=$name." ".$lastname." ".$secondLastName;

        $requestLog=new BuhoLegalLog();
        $requestLog->setStatus($status);
        $requestLog->setIp($remoteIp);
        $requestLog->setOrigin($requestOrigin);
        $requestLog->setUser($requestUser);
        $requestLog->setName($fullName);
        $requestLog->setExtra($extra);

        $this->entityManager->persist($requestLog);
        $this->entityManager->flush();
    }

}
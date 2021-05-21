<?php


namespace Logger\Middleware;


use Doctrine\ORM\EntityManager;
use Logger\Entity\RequestLog;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestLoggerMiddleware implements MiddlewareInterface
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response=$handler->handle($request);
        $params=$request->getQueryParams();

        $remoteIp=$request->getServerParams()['REMOTE_ADDR'] ?? NULL;
        $requestOrigin=$request->getHeaderLine('Request-Origin') ?? "";
        $requestUser=$request->getHeaderLine('Request-User') ?? "";
        $name=$params['name'] ?? "";
        $lastname=$params['lastname'] ?? "";
        $secondLastName=$params['secondLastName'] ?? "";
        $fullName=$name." ".$lastname." ".$secondLastName;

        $requestLog=new RequestLog();
        $requestLog->setStatus($response->getStatusCode());
        $requestLog->setIp($remoteIp);
        $requestLog->setRequestUrl($request->getUri()->getPath());
        $requestLog->setOrigin($requestOrigin);
        $requestLog->setUser($requestUser);
        $requestLog->setName($fullName);

        $this->entityManager->persist($requestLog);
        $this->entityManager->flush();

        return $response;
    }
}
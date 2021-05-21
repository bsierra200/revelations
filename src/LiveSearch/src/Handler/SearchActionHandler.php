<?php
/**
 * Created by PhpStorm.
 * User: carlosn
 * Date: 17/12/18
 * Time: 01:30 PM
 */

namespace LiveSearch\Handler;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;

class SearchActionHandler implements RequestHandlerInterface
{

    /**
     * Handle the request and return a response.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $name = $request->getAttribute('name', NULL);
        $nation = $request->getAttribute('nation', NULL);
        $city = $request->getAttribute('city', NULL);

        switch ($nation) {
            case "ecuador": {
                $command = "cd /home/ubuntu/scrappers/ && python bin/ecuador_funcionjudicial.py -n '{$name}'";
                exec($command, $output);
                if (isset($output[2])) {
                    $responseArray = json_decode($output[2], true);
                } else {
                    $responseArray = ['message' => "No se encontraron datos"];
                }
                break;
            }
            case "colombia": {
                $command = "cd /home/ubuntu/scrappers/ && ./bin/colombia.py --name='{$name}' --city='{$city}' --involvement=Demandado --involvement='demandado'";
                exec($command, $output);
                if (isset($output)) {
                    $responseArray = json_decode($output, true);
                } else {
                    $responseArray = ['message' => "No se encontraron datos"];
                }
                break;
            }
        }

        //Generate the response
        return new JsonResponse($responseArray);
    }
}
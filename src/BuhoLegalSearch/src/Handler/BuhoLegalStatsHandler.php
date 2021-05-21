<?php

namespace BuhoLegalSearch\Handler;


use BuhoLegalSearch\Service\StatsService;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class BuhoLegalStatsHandler implements RequestHandlerInterface
{
    /**
     * @var StatsService
     */
    protected $statsService;

    public function __construct(StatsService $statsService)
    {
        $this->statsService = $statsService;
    }


    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $datesRange = $this->getDatesRage($request);
        $stats = $this->statsService->getBlErrorsStats($datesRange['startDate'], $datesRange['endDate']);
        return new JsonResponse(['stats' => $stats]);
    }

    /**
     * @param ServerRequestInterface $request
     */
    private function getDatesRage(ServerRequestInterface $request)
    {
        $validateDate = function ($date, $format = 'Y-m-d') {
            $d = \DateTime::createFromFormat($format, $date);
            return $d && $d->format($format) == $date;
        };

        $now = new \DateTime();
        $startDate = $now->format("Y-m-d");
        $endDate = $startDate;

        $params = $request->getQueryParams();

        if (isset($params['startDate'])) {
            if ($validateDate($params['startDate'])) {
                $startDate = $params['startDate'];
                $endDate = $startDate;
            }
        }

        if (isset($params['endDate'])) {
            if ($validateDate($params['endDate'])) {
                $endDate = $params['endDate'];
            }
        }

        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
    }

}
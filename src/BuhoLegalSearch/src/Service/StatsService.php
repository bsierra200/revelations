<?php


namespace BuhoLegalSearch\Service;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Logger\Entity\BuhoLegalError;

class StatsService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * StatsService constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager=$entityManager;
    }

    /**
     * @param string $startDate
     * @param string $endDate
     * @return mixed
     */
    public function getBlErrorsStats(string $startDate,string $endDate)
    {
        $qb = $this->entityManager->getRepository(BuhoLegalError::class)->createQueryBuilder('e')
            ->select('e.type,count(e.errorId) as qty')
            ->where('e.date BETWEEN :startDate AND :endDate')
            ->groupBy('e.type')
            ->setParameter('startDate',$startDate." 00:00:00")
            ->setParameter('endDate',$endDate." 23:59:29");

        $results = $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);
        return $results;
    }

}
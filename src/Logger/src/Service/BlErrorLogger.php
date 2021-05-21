<?php
namespace Logger\Service;

use Doctrine\ORM\EntityManager;
use Logger\Entity\BuhoLegalError;

class BlErrorLogger
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * BlErrorLogger constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $type
     * @param string $detail
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function log(string $type,string $detail,string $name=NULL)
    {
        $error = new BuhoLegalError();
        $error->setType($type);
        $error->setDetail($detail);
        $error->setDate(new \DateTime());
        $error->setName($name);

        $this->entityManager->persist($error);
        $this->entityManager->flush();
    }

}
<?php

namespace App\Repository;

use App\Entity\Communication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Communication|null find($id, $lockMode = null, $lockVersion = null)
 * @method Communication|null findOneBy(array $criteria, array $orderBy = null)
 * @method Communication[]    findAll()
 * @method Communication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommunicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Communication::class);
    }

    public function findRealDurationCallsAfterDate(): string
    {
        return $this->createQueryBuilder('d')
            ->select('SEC_TO_TIME(SUM(TIME_TO_SEC(d.real_duration)))')
            ->where("d.date >= :date")
            ->andWhere('d.real_duration IS NOT NULL')
            ->setParameter('date', '2012-02-15')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findBilledDuration()
    {
        return $this->createQueryBuilder('d')
            ->select('d.billed_duration')
            ->where("d.time < :time1 OR d.time > :time2 ")
            ->andWhere('d.billed_duration IS NOT NULL')
            ->orderBy('d.billed_duration', 'DESC')
            ->setMaxResults(10)
            ->setParameter('time1', '08:00:00')
            ->setParameter('time2', '18:00:00')
            ->getQuery()
            ->getResult();
    }

    public function findAllSms()
    {
        return $this->createQueryBuilder('d')
            ->select('count(d)')
            ->where('d.real_duration IS NULL')
            ->getQuery()
            ->getSingleScalarResult();
    }

    // /**
    //  * @return Communication[] Returns an array of Communication objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Communication
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

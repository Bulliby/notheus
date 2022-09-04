<?php

namespace App\Repository;

use App\Entity\XList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<XList>
 *
 * @method XList|null find($id, $lockMode = null, $lockVersion = null)
 * @method XList|null findOneBy(array $criteria, array $orderBy = null)
 * @method XList[]    findAll()
 * @method XList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class XListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, XList::class);
    }

    public function add(XList $entity, bool $flush = false): int
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $entity->getId();
    }

    public function remove(XList $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return XList[] Returns an array of XList objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('x')
//            ->andWhere('x.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('x.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?XList
//    {
//        return $this->createQueryBuilder('x')
//            ->andWhere('x.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

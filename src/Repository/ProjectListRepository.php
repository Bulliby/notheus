<?php

namespace App\Repository;

use App\Entity\ProjectList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProjectList>
 *
 * @method ProjectList|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectList|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectList[]    findAll()
 * @method ProjectList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectList::class);
    }

    public function add(ProjectList $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProjectList $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getAutoIcrementId(): int
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_NAME = :tablename AND TABLE_SCHEMA = :database';
        $stmt = $conn->prepare($sql);

        $resultSet = $stmt->executeQuery([
            'tablename' => 'project_list',
            'database' => 'projects'
        ]);

        return $resultSet->fetchAssociative()['AUTO_INCREMENT'];
    }

//    /**
//     * @return ProjectList[] Returns an array of ProjectList objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ProjectList
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

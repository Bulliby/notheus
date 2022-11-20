<?php

namespace App\Repository;

use App\Entity\XList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\ArrayCollection;

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
        $pos = $this->getLastPosition();
        $entity->setPosition($pos);

        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $entity->getId();
    }

    private function getLastPosition()
    {
        $query = $this->createQueryBuilder('x')
                      ->select('x.position')
                      ->orderBy('x.position', 'DESC')
                      ->getQuery();

        $pos = $query->setMaxResults(1)->getOneOrNullResult();

        return $pos ? $pos['position'] + 1 : 1;
    }

    public function remove(XList $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function positions(ArrayCollection $cards, bool $flush = false): ArrayCollection
    {

        $id = 1;
        foreach($cards as $card) {
            $el = $this->getEntityManager()->getRepository(XList::class)->find($id);
            if ($el) {
                $el->setName($card->getName());
                $el->setPosition($card->getPosition());
                $this->getEntityManager()->persist($el);
            }
            $id++;
        }

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $cards;
    }

    public function countCards()
    {
        return count($this->getEntityManager()->getRepository(XList::class)->findAll());
    }
}

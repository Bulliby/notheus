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

    public function add(XList $entity): int
    {
        $entity->setPosition($this->getLastPosition());
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

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

    public function positions(array $requestCards): void
    {

        foreach($requestCards as $requestCard) {
            $card = $this->getEntityManager()->getRepository(XList::class)->findOneBy(['position' => $requestCard->getPosition()]);
            if (!$card) {
                throw new ValidationException("Position given in json is invalid", Response::HTTP_BAD_REQUEST);
            }

            $card->setName($requestCard->getName());
            $this->getEntityManager()->persist($card);
        }

        $this->getEntityManager()->flush();
    }

    public function countCards()
    {
        return count($this->getEntityManager()->getRepository(XList::class)->findAll());
    }
}

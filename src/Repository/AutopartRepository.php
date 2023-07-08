<?php

namespace App\Repository;

use App\Entity\Autopart;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Autopart>
 *
 * @method Autopart|null find($id, $lockMode = null, $lockVersion = null)
 * @method Autopart|null findOneBy(array $criteria, array $orderBy = null)
 * @method Autopart[]    findAll()
 * @method Autopart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AutopartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Autopart::class);
    }

    public function save(Autopart $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Autopart $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Autopart[] Returns an array of Autopart objects
     */
    public function getLast(int $amount): array
    {
        return $this->createQueryBuilder('a')
            ->select('a', 'c', 'w')
            ->join('a.car', 'c')
            ->join('a.warehouse', 'w')
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults($amount)
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getAutopartById(string $autopartId): ?Autopart
    {
        return $this->createQueryBuilder('a')
            ->select('a', 'c', 'w')
            ->join('a.car', 'c')
            ->join('a.warehouse', 'w')
            ->andWhere('a.autopartId = :autopartId')
            ->setParameter('autopartId', $autopartId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getFavoritesByUser(User $user)
    {
        return $this->createQueryBuilder('a')
            ->join('App\Entity\Favorite', 'f', Join::WITH, "f.autopart = a")
            ->andWhere('f.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function getInCartByUser(User $user)
    {
        return $this->createQueryBuilder('a')
            ->join('App\Entity\Cart', 'c', Join::WITH, "c.autopart= a")
            ->andWhere('c.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

//    public function findOneBySomeField($value): ?Autopart
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

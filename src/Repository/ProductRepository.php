<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Product $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Product $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getTotalProductSold($minDate,$maxDate){
        return $this->createQueryBuilder("p")
                    ->select('COUNT(command) as totalSales','p.label as product')
                    ->join('p.commands','command')
                    ->groupBy('p.label')
                    ->where('command.createdAt>= :date_min')
                    ->andWhere('command.createdAt<= :date_max')
                    ->setParameter('date_min',$minDate)
                    ->setParameter('date_max',$maxDate)
                    ->orderBy('COUNT(command)','DESC')
                    ->getQuery()->getResult();

    }
    public function getThreeMostSellProduct(){
        return $this->createQueryBuilder("p")
                    ->select('COUNT(command) as totalSales','p as product')
                    ->join('p.commands','command')
                    ->groupBy('p.label')
                    ->setMaxResults(3)
                    ->orderBy('COUNT(command)','DESC')
                    ->getQuery()->getResult();

    }

    public function getQbAll()
    {
        return $this->createQueryBuilder('p')
        ->orderBy('p.id','asc');
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

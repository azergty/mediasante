<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\BookRenting;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<BookRenting>
 *
 * @method BookRenting|null find($id, $lockMode = null, $lockVersion = null)
 * @method BookRenting|null findOneBy(array $criteria, array $orderBy = null)
 * @method BookRenting[]    findAll()
 * @method BookRenting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRentingRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */

    public function __construct(ManagerRegistry $registry,PaginatorInterface $paginator)
    {
        parent::__construct($registry, BookRenting::class);
        $this->paginator = $paginator;
    }

    public function add(BookRenting $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BookRenting $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    /**
     * Récupère les Livres loués
     * @return PaginationInterface
     */

    public function findAllRentingBooksForAdministration($page,$limit=9,$pageName ='page'):PaginationInterface
    {
        $query = $this
            ->createQueryBuilder('br')
            ->select('br' )
            ->where('br.renting_end IS NULL')
        ;
//        $this->paginator->
        return $this->paginator->paginate(
            $query,
            $page,
            $limit,
            array(
                'pageParameterName' => (string) $pageName
            )
        );
    }


//    /**
//     * @return BookRenting[] Returns an array of BookRenting objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BookRenting
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

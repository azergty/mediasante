<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Author;
use App\Entity\Book;
use App\Entity\BookRenting;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry,PaginatorInterface $paginator )
    {
        parent::__construct($registry, Book::class);
        $this->paginator = $paginator;

    }

    public function add(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Book[] Returns an array of Book objects
     */
    public function getBooks($order_by=array(), $limit = null, $offset = null)
    {
        $db =  $this->createQueryBuilder('a');

        $db->addSelect('author')
        ->leftJoin('a.author', 'author');
        $db->addSelect('category')
            ->leftJoin('a.category', 'category');
        $db->addSelect("bookrenting")
            ->leftJoin('a.bookRentings', 'bookrenting');

        if($limit) $db->setMaxResults((int) $limit);
        if($offset) $db->setFirstResult((int) $offset);

        $query=$db->getQuery();
        echo $query->getParameters()."\n";
        echo $query->getSQL()."\n";
//        exit();


        return  $query
            ->getResult();

    }



    public function remove(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Récupère les Livres en lien avec une recherche
     * @return PaginationInterface
     */

    public function findSearch(SearchData $search,$pageName='page'):PaginationInterface
    {

        $query = $this
            ->createQueryBuilder('b')
            ->select('c', 'a', 'b')
            ->join('b.category', 'c')
            ->join('b.author', 'a');

        if (!empty($search->reference)) {
            $query = $query
                ->andWhere('b.reference LIKE :q')
                ->setParameter('q', "%{$search->reference}%");
        }
        if (!empty($search->author)) {
            $query =$query
                ->andWhere('a.last_name LIKE :author')
                ->setParameter('author', "%{$search->author}%");
        }

        if (!empty($search->categories)) {
            $query = $query
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->categories);
        }

        $qb2 = $this->_em->createQueryBuilder();
        $qb2->select('identity(ms.book)')
            ->from(BookRenting::class, 'ms')
            ->where('ms.renting_end IS NULL');

        $query = $query
            ->andwhere($query->expr()->notIn('b.id',  $qb2->getDQL()));

        return $this->paginator->paginate(
            $query,
            $search->page,
            $search->limit,
            array(
                'pageParameterName' => (string) $pageName
            )
        );


    }


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

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

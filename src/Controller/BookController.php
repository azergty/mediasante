<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Book;
use App\Entity\BookRenting;
use App\Entity\Category;
use App\Entity\CollectionOfCategories;
use App\Form\BookType;
use App\Form\CollectionCategoriesType;
use App\Form\SearchBookType;
use App\Repository\BookRentingRepository;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Flex\Response;
use Knp\Component\Pager\PaginatorInterface;

class BookController extends AbstractController
{

    /**
     * @Route("/book/rent/{book_id<\d+>}", name="app_rent_book", methods={"GET"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function rentBook($book_id,ManagerRegistry $doctrine) : \Symfony\Component\HttpFoundation\Response
    {

        $response=array(
            'success'=>false,
            'message'=>'Unknow error'
        );
        $u = $this->getUser();

        $entityManager = $doctrine->getManager();

        // Veify if book exist in BDD
        $book = $entityManager->getRepository(Book::class)->find((int) $book_id);

        if(!$book) {
            $response['message'] = "Le livre n'existe pas";
        }else{

            // Veify if book isn't rent
            $book_is_renting = $entityManager->getRepository(BookRenting::class)->findBy(['book'=>(int) $book_id,'renting_end'=>null]);

            if(count($book_is_renting)==1){
                $date =  $book_is_renting[0]->getLimitDate();

                // add on day to renting end date
                $delivery_date = date('d/m/Y',strtotime($date->format('Y-m-d H:i:s'))+(3600*24));

                $response['message'] = "Le livre est déjà loué et devrait être disponible à partir du ".$delivery_date;

            }else{
                // Save renting book in bdd
                $date = new \DateTimeImmutable();
                $book_renting = new BookRenting();
                $book_renting->setBook($book);
                $book_renting->setRentingStart($date);
                $book_renting->setUser($u);
                $entityManager->persist($book_renting);
                $entityManager->flush();
                $response['message'] = "Le livre à bien été ajouté à votre panier";
                $response['success']=true;
            }

        }
        $this->addFlash('RENT_BOOK_SUCCESS', $response);
        return $this->redirectToRoute('app_shop_book',[], 302 );
    }

    /**
     * @Route("/book/return/{book_id<\d+>}", name="app_return_book")
     * @Security("is_granted('ROLE_USER')")
     */
    public function returnBook($book_id,ManagerRegistry $doctrine,BookRentingRepository $bookRentingRepository) :\Symfony\Component\HttpFoundation\Response
    {

        $response=array(
            'success'=>false,
            'message'=>'Unknow error'
        );
        $u = $this->getUser();
        $entityManager = $doctrine->getManager();

        // Veify if book exist in BDD
        $book = $entityManager->getRepository(Book::class)->find((int) $book_id);

        if(!$book) {
            $response['message'] = "Le livre n'existe pas";
        }else{

            // Veify if book isn't rent
            $book_is_renting = $bookRentingRepository->findBy(['renting_end'=>null,'book'=>$book]);

            if(empty($book_is_renting)){
                $response['message'] = "Le livre <<".$book->getReference().">> n'est actuellement pas loué";
            }else{
                // Verify id user correspondance
                if($book_is_renting[0]->getUser()->getId() != $u->getId()){
                    $response['message'] = "Vous n'avez jamais emprunté ce livre !";
                }else{
                    // Save return book in bdd
                    $date = new \DateTimeImmutable();
                    $book_is_renting[0]->setRentingEnd($date);
                    $entityManager->persist($book_is_renting[0]);
                    $entityManager->flush();

                    $response['message'] = "Le livre <<".$book_is_renting[0]->getBook()->getReference().">> a été rendu avec succès. Vous pouvez dès à présent le ranger dans la bibliothèque";
                    $response['success']=true;
                }
            }
        }
        $this->addFlash('RETURN_BOOK_SUCCESS', $response);
        return $this->redirectToRoute('app_account',[], 302 );
    }


    /**
     * @Route("/retrouver-tous-nos-livres-en-boutique", name="app_shop_book")
     */
    public function app_shop_book(BookRepository $bookrepository,Request $request, PaginatorInterface $paginator) : \Symfony\Component\HttpFoundation\Response
    {

        $data = new SearchData();
        $form = $this->createForm(SearchBookType::class,$data);
        $form->handleRequest($request);
        $data->page = $request->query->getInt('page', 1);
//        print_r($form->getData());
        $books = $bookrepository->findSearch($data);




        return $this->render('book/index.html.twig',
        [
           'book_form' => $form->createView(),
           'books'=>$books #$books['books']
        ]);
    }

}

<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\BookRenting;
use App\Repository\BookRentingRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class UserController extends AbstractController
{

    /**
     * @Route("/account", name="app_account")
     * @Security("is_granted('ROLE_USER')")
   */
    public function index(ManagerRegistry $doctrine) : Response
    {
//        $this->denyAccessUnlessGranted('ROLE_USER');
        $u = $this->getUser();
        $entityManager = $doctrine->getManager();


        $RentingBooks = $entityManager->getRepository(BookRenting::class)->findBy(array('user'=>$u),array('renting_start'=>'DESC'));


        return $this->render('users/account.html.twig',[
                'rentingbooks'=>$RentingBooks,
                "user"=>$u
                ]
            );
    }


}

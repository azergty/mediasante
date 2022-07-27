<?php

namespace App\Controller;

use App\Repository\BookRentingRepository;
use App\Repository\BookRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AdministratorController extends AbstractController
{

    /**
     * @Route("/administration", name="app_admin", methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index(Request $request,UserRepository $userRepository,BookRentingRepository $bookRentingRepository) : Response
    {
        $userPageName="page";
        $page = $request->query->getInt($userPageName, 1);

        $booksPageName='p';
        $booksPage = $request->query->getInt($booksPageName, 1);

        // PAgination object of all users
        $users = $userRepository->getAllUserForAdministration($page,9,$userPageName);

        // PAgination object of all renting books
        $rentedbooks = $bookRentingRepository->findAllRentingBooksForAdministration($booksPage,3,$booksPageName);

        return $this->render("admin/index.html.twig",[
            'users'=>$users,
            'rentedBooks'=>$rentedbooks
        ]);
    }

}

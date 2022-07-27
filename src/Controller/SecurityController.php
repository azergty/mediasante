<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use Symfony\Component\HttpFoundation\Request;
use App\Form\UserType;
use App\Entity\User;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class SecurityController extends AbstractController
{

    /**
     * @Route("/register", name="app_register")
     */

    public function register(UserPasswordHasherInterface $passwordHasher,Request $request,ManagerRegistry $doctrine,ValidatorInterface $validator): Response{
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);
        $errors = array();

        if($form->isSubmitted() && $form->isValid()) {

            $contactFormData = $form->getData();
//            print_r($contactFormData);
            $u1 = new User($contactFormData);

            $errors = $validator->validate($u1);

            if(count($errors) == 0){
                $entityManager = $doctrine->getManager();

                $user = new User();
                $user->setLastName($contactFormData['last_name']);
                $user->setFirstName($contactFormData['first_name']);
                $user->setEmail($contactFormData['email']);
                $user->setPhone($contactFormData['phone']);
                $date = new \DateTimeImmutable();
                $user->setRoles(["ROLE_USER"]);
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $contactFormData['password']
                );
                $user->setPassword($hashedPassword);
                $user->setCreatedAt($date);

                // tell Doctrine you want to (eventually) save the Product (no queries yet)
                $entityManager->persist($user);

                // actually executes the queries (i.e. the INSERT query)
                $entityManager->flush();
                // regsiter user

                $this->addFlash('success', 'Votre compte à bien été crée avec succès');


                $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                $this->get('security.token_storage')->setToken($token);
                $this->get('session')->set('_security_main', serialize($token));

                return $this->redirectToRoute('app_account',  [], 302 );
            }

        }


        return $this->render('users/register.html.twig', [
            'our_form' => $form->createView(),
            'errors'=>$errors
        ]);
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        if ($this->getUser()) {
            return $this->redirectToRoute('app_account');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastEmail = $authenticationUtils->getLastUsername();


        return $this->render('security/login.html.twig', ['last_email' => $lastEmail, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}

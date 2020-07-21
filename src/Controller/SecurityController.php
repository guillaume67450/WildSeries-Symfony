<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Security;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    /**
     * @Route("/register", name="app_register")
     */
     public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
     {
        $user = $this->getUser();

        if($user <> null) {
            $this->addFlash(
                'warning',
                "On ne peut pas accéder à la page d'inscription si on est connecté avec un compte !"
            );

            return $this->redirectToRoute('app_index');
        }

         $user = new User();
         $form = $this->createForm(RegistrationFormType::class, $user);
         $form->handleRequest($request);
 
         if ($form->isSubmitted() && $form->isValid()) {
             // encode the plain password
             $user->setPassword(
                 $passwordEncoder->encodePassword(
                     $user,
                     $form->get('plainPassword')->getData()
                 )
             );
 
             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($user);
             $entityManager->flush();
 
             // do anything else you need here, like send an email
 
             return $guardHandler->authenticateUserAndHandleSuccess(
                 $user,
                 $request,
                 $authenticator,
                 'main' // firewall name in security.yaml
             );
         }
 
         return $this->render('security/register.html.twig', [
             'registrationForm' => $form->createView(),
         ]);
     }

    /**
     * Permet d'afficher le profil de l'utilisateur connecté
     * 
     * @Route("/my-profile", name="profile")
     * IsGranted("ROLE_USER")
     * 
     * @return Response
     */    
    public function myAccount() {
        return $this->render('security/profile.html.twig', [
            'user' => $this->getUser()
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\ForgotPasswordType;
use App\Form\Type\RegistrationType;
use App\Form\Type\ResetPasswordType;
use App\Manager\UserManager;
use App\Service\Mailer;
use App\Service\PasswordEncoder;
use App\Service\TokenGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/logout", name="logout")
     */
    public function logout(): void
    {
        throw new \LogicException('Correctly logout!');
    }

    /**
     * @Route("/login", name="login", methods={"GET","POST"})
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('snow_tricks_homepage');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('User/Security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/registration", name="snow_tricks_user_registration", methods={"GET","POST"})
     */
    public function registerAction(Request $request, Mailer $mailer, TokenGenerator $tokenGenerator, UserManager $userManager, PasswordEncoder $passwordEncoder)
    {
        $user = $userManager->initUser();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userManager->createUser($user);

            $this->addFlash(
                'notice',
                'An email sent to you for verify account !'
            );

            return $this->redirectToRoute('snow_tricks_homepage');
        }

        return $this->render('User/Security/registration.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/token/{token}", name="snow_tricks_user_tokenVerification", methods={"GET"})
     */
    public function tokenVerificationAction($token, UserManager $userManager)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->findOneBy(['token' => $token]);

        $userManager->activeUser($user);

        return $this->redirectToRoute('snow_tricks_homepage');
    }

    /**
     * @Route("/forgot_password", name="snow_tricks_user_forgotPassword", methods={"GET","POST"})
     */
    public function forgotPasswordAction(Request $request, UserManager $userManager)
    {
        $form = $this->createForm(ForgotPasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $repository = $this->getDoctrine()->getRepository(User::class);

            $user = $repository->findOneBy(
                array('username' => $request->get('username'))
            );

            if(!is_null($user))
                $userManager->resetPassword($user);

            $this->addFlash(
                'notice',
                'An email send to you for reset password !'
            );

            return $this->redirectToRoute('snow_tricks_user_forgotPassword');
        }

        return $this->render('User/Security/forgot_password.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/reset_password/{token}", name="snow_tricks_user_resetPassword", methods={"GET","POST"})
     */
    public function resetPasswordAction(Request $request, PasswordEncoder $passwordEncoder, UserManager $userManager)
    {

        $repository = $this->getDoctrine()->getRepository(User::class);
        $userToken = $repository->findOneBy(
            array('token' => $request->get('token'))
        );

        // si aucun token correspondant
        if (!$userToken) {
            // renvoi sur home avec message
            $this->addFlash(
                'notice',
                'No ask for change password for you !'
            );
            return $this->redirectToRoute('snow_tricks_homepage');
        }

        // creation du formulaire
        $form = $this->createForm(ResetPasswordType::class);

        // si formulaire rempli et valide
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // on récupére le user via email du form
            $repository = $this->getDoctrine()->getRepository(User::class);

            $user = $repository->findOneBy(
                array('email' => $request->get('email'))
            );

            $userManager->updatePassword($user, $userToken, $request->get('password'));

            // renvoi sur home avec message
            $this->addFlash(
                'notice',
                'Password has been changed !'
            );
            return $this->redirectToRoute('snow_tricks_homepage');
        }

        return $this->render('User/Security/reset_password.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}

<?php

namespace SnowTricks\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SnowTricks\AppBundle\Entity\User;
use SnowTricks\AppBundle\Form\Type\ForgotPasswordType;
use SnowTricks\AppBundle\Form\Type\RegistrationType;
use SnowTricks\AppBundle\Form\Type\ResetPasswordType;
use SnowTricks\AppBundle\Manager\UserManager;
use SnowTricks\AppBundle\Service\Mailer;
use SnowTricks\AppBundle\Service\PasswordEncoder;
use SnowTricks\AppBundle\Service\TokenGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        // Si le visiteur est déjà identifié, on le redirige vers l'accueil
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('snow_tricks_homepage');
        }

        // Le service authentication_utils permet de récupérer le nom d'utilisateur
        // et l'erreur dans le cas où le formulaire a déjà été soumis mais était invalide
        // (mauvais mot de passe par exemple)
        $authenticationUtils = $this->get('security.authentication_utils');

        return $this->render('@SnowTricksApp/User/Security/login.html.twig', array(
            'last_username' => $authenticationUtils->getLastUsername(),
            'error'         => $authenticationUtils->getLastAuthenticationError(),
        ));
    }

    /**
     * @Route("/registration", name="snow_tricks_user_registration")
     */
    public function registerAction(Request $request, Mailer $mailer, TokenGenerator $tokenGenerator, UserManager $userManager, PasswordEncoder $passwordEncoder)
    {
        $user = $userManager->initUser();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setToken($tokenGenerator->generateToken($user));

            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));

            $userManager->saveUser($user);

            $mailer->sendMail($user, 'Confirm account', 'registration');

            $this->addFlash(
                'notice',
                'An email sent to you for verify account !'
            );

            return $this->redirectToRoute('snow_tricks_homepage');
        }

        return $this->render('@SnowTricksApp/User/Security/registration.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/token/{token}", name="snow_tricks_user_tokenVerification")
     */
    public function tokenVerificationAction($token, UserManager $userManager)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->findOneBy(['token' => $token]);

        $userManager->activeUser($user);

        return $this->redirectToRoute('snow_tricks_homepage');
    }

    /**
     * @Route("/forgot_password", name="snow_tricks_user_forgotPassword")
     */
    public function forgotPasswordAction(Request $request, Mailer $mailer, TokenGenerator $tokenGenerator, UserManager $userManager)
    {
        $form = $this->createForm(ForgotPasswordType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository = $this->getDoctrine()->getRepository(User::class);

            $user = $repository->findOneBy(
                array('username' => $request->get('username'))
            );

            if ($user) {

                $user->setToken($tokenGenerator->generateToken($user));

                $userManager->saveUser($user);

                $mailer->sendMail($user, 'Reset your password', 'forgot_password');
            }

            $this->addFlash(
                'notice',
                'An email send to you for reset password !'
            );

            return $this->redirectToRoute('snow_tricks_user_forgotPassword');
        }

        return $this->render('@SnowTricksApp/User/Security/forgot_password.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/reset_password/{token}", name="snow_tricks_user_resetPassword")
     */
    public function resetPasswordAction(Request $request, PasswordEncoder $passwordEncoder, UserManager $userManager)
    {

        // on récupére le userToken
        $token = $request->get('token');

        $repository = $this->getDoctrine()->getRepository(User::class);
        $userToken = $repository->findOneBy(
            array('token' => $token)
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

            // compare si userToken et user === username
            if ($user->getUsername() === $userToken->getUsername()) {

                // on modifie le mot de passe de user
                $user->setPassword($passwordEncoder->encodePassword($user, $request->get('password')));

                $userManager->saveUser($user);
            }

            // renvoi sur home avec message
            $this->addFlash(
                'notice',
                'Password has been changed !'
            );
            return $this->redirectToRoute('snow_tricks_homepage');
        }

        return $this->render('@SnowTricksApp/User/Security/reset_password.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
